<?php

namespace App\Tests\Infrastructure;

use App\Domain\Entity\Article;
use App\Domain\Entity\Author;
use App\Infrastructure\Interfaces\ArticleRepositoryInterface;
use App\Infrastructure\Interfaces\AuthorRepositoryInterface;
use App\Infrastructure\Repository\MySQLArticleRepository;
use App\Infrastructure\Repository\MySQLAuthorRepository;
use App\Tests\Aplication\Article\ArticleGetTest;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AbstractTest extends KernelTestCase
{
    protected ContainerInterface $container;

    protected AuthorRepositoryInterface $authorRepository;
    protected ArticleRepositoryInterface $articleRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->container = static::getContainer();
        $articles = ArticleGetTest::generateMockArticles();

        $this->articleRepository = $this->createMock(MySQLArticleRepository::class);

        // Mocks an array of user Response
        $this->articleRepository->expects($this->any())->method('getAll')->willReturn($articles);

        // Mocks User response
        $this->articleRepository->expects($this->any())->method('getOne')
            ->willReturnCallback(fn (string $value) => match (true) {
                'NO_EXIST_UUID' === $value => null,
                default => $articles[0]
            });

        $authors = self::generateMockAuthors();
        $this->authorRepository = $this->createMock(MySQLAuthorRepository::class);

        // Mocks an array of user Response
        $this->authorRepository->expects($this->any())->method('getAll')->willReturn($authors);

        // Mocks User response
        $this->authorRepository->expects($this->any())->method('getOne')
            ->willReturnCallback(fn (string $value) => match (true) {
                'NO_EXIST_UUID' === $value => null,
                default => $authors[0]
            });
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
}
