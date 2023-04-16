<?php

namespace App\Tests\Aplication\Abstracts;

use App\Domain\Entity\Article;
use App\Infrastructure\Repository\MySQLArticleRepository;
use App\Tests\Aplication\Author\AuthorGetTest;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractGetTest extends KernelTestCase
{
    protected MySQLArticleRepository $articleRepositoryMock;

    /** @var array <int,Article> */
    protected array $articles = [];

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
        $this->articles = $this->generateMockArticles();
        $this->articleRepositoryMock = $this->createMock(MySQLArticleRepository::class);

        // Mocks an array of user Response
        $this->articleRepositoryMock->expects($this->any())->method('getAll')->willReturn($this->articles);

        // Mocks User response
        $this->articleRepositoryMock->expects($this->any())->method('getOne')
            ->willReturnCallback(fn (string $value) => match (true) {
                'NO_EXIST_UUID' === $value => null,
                default => $this->articles[array_rand($this->articles)]
            });
    }

    abstract public function testGet(): void;

    abstract public function testGetOne(): void;

    abstract public function testGetNotExist(): void;
}
