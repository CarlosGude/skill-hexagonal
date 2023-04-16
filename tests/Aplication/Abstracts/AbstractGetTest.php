<?php

namespace App\Tests\Aplication\Abstracts;

use App\Domain\Entity\Article;
use App\Domain\Entity\Author;
use App\Infrastructure\Interfaces\ArticleRepositoryInterface;
use App\Infrastructure\Interfaces\AuthorRepositoryInterface;
use App\Infrastructure\Repository\MySQLArticleRepository;
use App\Infrastructure\Repository\MySQLAuthorRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractGetTest extends KernelTestCase
{
    protected ArticleRepositoryInterface $articleRepository;
    protected AuthorRepositoryInterface $authorRepository;

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
        $articles = $this->generateMockArticles();
        $this->articleRepository = $this->createMock(MySQLArticleRepository::class);

        // Mocks an array of user Response
        $this->articleRepository->expects($this->any())->method('getAll')->willReturn($articles);

        // Mocks User response
        $this->articleRepository->expects($this->any())->method('getOne')
            ->willReturnCallback(fn (string $value) => match (true) {
                'NO_EXIST_UUID' === $value => null,
                default => $articles[array_rand($articles)]
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

    abstract public function testGet(): void;

    abstract public function testGetOne(): void;

    abstract public function testGetNotExist(): void;
}
