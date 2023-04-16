<?php

namespace App\Tests\Aplication\Abstracts;

use App\Domain\Entity\Article;
use App\Infrastructure\Repository\MySQLArticleRepository;
use App\Infrastructure\Repository\MySQLAuthorRepository;
use App\Tests\Aplication\Author\AuthorGetTest;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractPostTest extends KernelTestCase
{
    protected MySQLArticleRepository $articleRepositoryMock;
    protected MySQLAuthorRepository $authorRepositoryMock;

    /**
     * @return array <int, Article>
     */
    public static function generateMockArticles(): array
    {
        $articles = [];
        foreach (AuthorGetTest::generateMockUsers() as $author) {
            for ($i = 0; $i <= rand(5, 10); ++$i) {
                $articles[] = (new Article($author))
                    ->setTitle('TEST ARTICLE '.$i)
                    ->setBody('TEST ARTICLE BODY')
                ;
            }
        }

        return $articles;
    }

    protected function setUp(): void
    {
        $articles = $this->generateMockArticles();
        $authors = AuthorGetTest::generateMockUsers();

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
