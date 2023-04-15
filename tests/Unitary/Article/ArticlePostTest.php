<?php

namespace App\Tests\Unitary\Article;

use App\Application\Articles\DataTransformer\Input\ArticleDataTransformer as InputArticleDataTransformer;
use App\Application\Articles\DataTransformer\Output\ArticleDataTransformer as OutputArticleDataTransformer;
use App\Application\Articles\Dto\Output\ArticleDto;
use App\Application\Articles\UseCase\PostArticleUseCase;
use App\Application\Articles\Validation;
use App\Application\Authors\DataTransformer\Output\AuthorDataTransformer;
use App\Application\Authors\Dto\Output\AuthorDto;
use App\Tests\Unitary\Abstracts\AbstractPostTest;

class ArticlePostTest extends AbstractPostTest
{
    protected PostArticleUseCase $postArticleUseCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->postArticleUseCase = new PostArticleUseCase(
            articleInputDataTransformer: new InputArticleDataTransformer($this->authorRepositoryMock, new AuthorDataTransformer()),
            articleOutputDataTransformer: new OutputArticleDataTransformer(new AuthorDataTransformer()),
            articleRepository: $this->articleRepositoryMock,
            validation: new Validation()
        );
    }

    public function testSuccessPost(): void
    {
        $request = ['title' => 'Title test', 'body' => 'Body test', 'author' => 'uuid'];
        $article = $this->postArticleUseCase->post($request);
        $this->assertInstanceOf(ArticleDto::class, $article);
        $this->assertEquals($article->getTitle(), $request['title']);
        $this->assertEquals($article->getBody(), $request['body']);
        $this->assertInstanceOf(AuthorDto::class, $article->getAuthor());
        $this->assertSame($article->getAuthor()->getEmail(), filter_var($article->getAuthor()->getEmail(), FILTER_VALIDATE_EMAIL));
    }

    public function testErrorPost(): void
    {
        $request = [];
        $this->expectException(\Exception::class);
        $this->postArticleUseCase->post($request);
    }
}
