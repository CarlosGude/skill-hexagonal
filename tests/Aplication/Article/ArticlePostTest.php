<?php

namespace App\Tests\Aplication\Article;

use App\Application\Abstracts\AbstractValidator;
use App\Application\Articles\DataTransformer\Input\ArticleDataTransformer as InputArticleDataTransformer;
use App\Application\Articles\DataTransformer\Output\ArticleDataTransformer as OutputArticleDataTransformer;
use App\Application\Articles\Dto\Output\ArticleDto;
use App\Application\Articles\UseCase\PostArticleUseCase;
use App\Application\Articles\Validation;
use App\Application\Authors\DataTransformer\Output\AuthorDataTransformer;
use App\Application\Authors\Dto\Output\AuthorDto;
use App\Application\Exceptions\AuthorNotFoundException;
use App\Application\Exceptions\BodyRequestException;
use App\Application\Exceptions\DtoValidationException;
use App\Tests\Aplication\Abstracts\AbstractPostTest;

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

    /**
     * @throws \Exception
     */
    public function testErrorPost(): void
    {
        $request = [];
        $this->expectException(BodyRequestException::class);
        $this->postArticleUseCase->post($request);
    }

    public function testRequestAuthorErrorNullPost(): void
    {
        $request = ['title' => null, 'body' => null, 'author' => null];
        $this->expectException(AuthorNotFoundException::class);
        $this->postArticleUseCase->post($request);
    }

    public function testRequestErrorAuthorNullPost(): void
    {
        $request = ['title' => null, 'body' => null, 'author' => null];
        $this->expectException(AuthorNotFoundException::class);
        $this->postArticleUseCase->post($request);
    }

    public function testRequestErrorTitleAndBodyNullOrEmptyNullPost(): void
    {
        $request = ['title' => null, 'body' => '', 'author' => 'uuid'];
        try {
            $this->postArticleUseCase->post($request);
        } catch (DtoValidationException $exception) {
            /** @var array<string,array<string,string>> $errors */
            $errors = $exception->getErrors();

            $this->assertArrayHasKey(AbstractValidator::INDEX_NULL, $errors['title']);
            $this->assertArrayHasKey(AbstractValidator::INDEX_NULL, $errors['body']);
            $this->assertEquals(AbstractValidator::VALUE_NULL, $errors['title'][AbstractValidator::INDEX_NULL]);
            $this->assertEquals(AbstractValidator::VALUE_NULL, $errors['body'][AbstractValidator::INDEX_NULL]);
            $this->assertArrayNotHasKey('author', $errors);
        }
    }

    public function testRequestErrorTitleAndBodyIsEmptyNullPost(): void
    {
        $request = ['title' => 'Title test', 'body' => 'Body test', 'author' => 'NO_EXIST_UUID'];
        $this->expectException(AuthorNotFoundException::class);
        $this->postArticleUseCase->post($request);
    }
}
