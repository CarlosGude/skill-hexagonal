<?php

namespace App\Tests\Aplication\Abstracts;

use App\Domain\Entity\Article;
use App\Domain\Entity\Author;
use App\Infrastructure\Repository\MySQLArticleRepository;
use App\Infrastructure\Repository\MySQLAuthorRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractPostTest extends KernelTestCase
{
    protected MySQLArticleRepository $articleRepositoryMock;
    protected MySQLAuthorRepository $authorRepositoryMock;
    protected ContainerInterface $container;

    /**
     * @return array <int, Article>
     */
    public static function generateMockArticles(): array
    {
        $articles = [];
        foreach (self::generateMockAuthors() as $author) {
            for ($i = 0; $i <= rand(5, 10); ++$i) {
                $articles[] = (new Article($author))
                    ->setTitle('TEST ARTICLE '.$i)
                    ->setBody('TEST ARTICLE BODY')
                ;
            }
        }

        return $articles;
    }

    /**
     * @return array <int, Author>
     */
    public static function generateMockAuthors(): array
    {
        $authors = [];
        for ($i = 0; $i <= rand(5, 10); ++$i) {
            $author = (new Author())
                ->setName('User name '.$i)
                ->setEmail("user$i@email.com")
            ;

            for ($i = 0; $i <= rand(1, 10); ++$i) {
                $author->addArticle(
                    (new Article($author))
                        ->setTitle('Title '.$i)
                        ->setBody('Body '.$i)
                );
            }

            $authors[] = $author;
        }

        return $authors;
    }

    protected function setUp(): void
    {
        $articles = self::generateMockArticles();
        $authors = self::generateMockAuthors();

        $this->container = static::getContainer();

        $this->articleRepositoryMock = $this->getMockBuilder(MySQLArticleRepository::class)
            ->onlyMethods(['getAll', 'getOne'])
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->authorRepositoryMock = $this->createMock(MySQLAuthorRepository::class);

        $this->authorRepositoryMock->expects($this->any())->method('getAll')->willReturn($authors);

        // Mocks User response
        $this->authorRepositoryMock->expects($this->any())->method('getOne')
            ->willReturnCallback(fn (string $value) => match (true) {
                'NO_EXIST_UUID' === $value => null,
                default => $authors[0]
            });

        // Mocks an array of user Response
        $this->articleRepositoryMock->expects($this->any())->method('getAll')->willReturn($articles);

        // Mocks User response
        $this->articleRepositoryMock->expects($this->any())->method('getOne')
            ->willReturnCallback(fn (string $value) => match (true) {
                'NO_EXIST_UUID' === $value => null,
                default => $articles[0]
            });
    }

    abstract public function testSuccessPost(): void;

    abstract public function testErrorPost(): void;
}
