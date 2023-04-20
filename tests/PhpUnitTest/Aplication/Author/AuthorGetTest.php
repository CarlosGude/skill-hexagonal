<?php

namespace App\Tests\PhpUnitTest\Aplication\Author;

use App\Application\Articles\Dto\Output\ArticleDto;
use App\Application\Authors\DataTransformer\Output\AuthorDataTransformer;
use App\Application\Authors\Dto\Output\AuthorDto;
use App\Application\Authors\UseCase\GetAuthorsUseCase;
use App\Application\Exceptions\AuthorNotFoundException;
use App\Tests\PhpUnitTest\Infrastructure\AbstractTest;

class AuthorGetTest extends AbstractTest
{
    protected GetAuthorsUseCase $authorsGetUseCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authorsGetUseCase = new GetAuthorsUseCase(
            authorRepository: $this->authorRepository,
            transformer: new AuthorDataTransformer()
        );
    }

    /**
     * @throws AuthorNotFoundException
     */
    public function testGet(): void
    {
        $authors = $this->authorsGetUseCase->getAll();
        $this->assertNotEmpty($authors);
        $this->assertIsArray($authors);
        $author = $authors[0];
        $this->assertInstanceOf(AuthorDto::class, $author);
        $this->assertIsArray($author->getArticles());
        $this->assertNotEmpty($author->getArticles());
        $this->assertInstanceOf(ArticleDto::class, $author->getArticles()[0]);
    }

    /**
     * @throws AuthorNotFoundException
     */
    public function testGetOne(): void
    {
        $author = $this->authorsGetUseCase->get('uuid');
        $this->assertNotEmpty($author);
        $this->assertIsNotArray($author);
        $this->assertInstanceOf(AuthorDto::class, $author);
        $this->assertIsArray($author->getArticles());
        $this->assertNotEmpty($author->getArticles());
        $this->assertInstanceOf(ArticleDto::class, $author->getArticles()[0]);
    }

    public function testGetOneNoExist(): void
    {
        $this->expectException(AuthorNotFoundException::class);
        $this->authorsGetUseCase->get('NO_EXIST_UUID');
    }
}
