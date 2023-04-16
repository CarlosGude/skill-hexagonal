<?php

namespace App\Tests\Aplication\Article;

use App\Application\Articles\DataTransformer\Output\ArticleDataTransformer;
use App\Application\Articles\Dto\Output\ArticleDto;
use App\Application\Articles\UseCase\GetArticleUseCase;
use App\Application\Authors\DataTransformer\Output\AuthorDataTransformer;
use App\Application\Authors\Dto\Output\AuthorDto;
use App\Application\Exceptions\ArticleNotFoundException;
use App\Tests\Aplication\Abstracts\AbstractGetTest;

class ArticleGetTest extends AbstractGetTest
{
    protected GetArticleUseCase $getArticleUseCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->getArticleUseCase = new GetArticleUseCase(
            articleRepository: $this->articleRepository,
            transformer: new ArticleDataTransformer(new AuthorDataTransformer())
        );
    }

    /**
     * @throws ArticleNotFoundException
     */
    public function testGet(): void
    {
        $articles = $this->getArticleUseCase->getAll();
        $this->assertNotEmpty($articles);
        $this->assertIsArray($articles);
        $this->assertInstanceOf(ArticleDto::class, $articles[0]);
        $this->assertInstanceOf(AuthorDto::class, $articles[0]->getAuthor());
    }

    /**
     * @throws ArticleNotFoundException
     */
    public function testGetOne(): void
    {
        $article = $this->getArticleUseCase->get('uuid');
        $this->assertIsNotArray($article);
        $this->assertInstanceOf(ArticleDto::class, $article);
        $this->assertInstanceOf(AuthorDto::class, $article->getAuthor());
    }

    public function testGetNotExist(): void
    {
        $this->expectException(ArticleNotFoundException::class);
        $this->getArticleUseCase->get('NO_EXIST_UUID');
    }
}
