<?php

namespace App\Tests\Aplication\Article;

use App\Application\Articles\DataTransformer\Input\ArticleDataTransformer as InputArticleDataTransformer;
use App\Application\Articles\DataTransformer\Output\ArticleDataTransformer as OutputArticleDataTransformer;
use App\Application\Articles\Dto\Output\ArticleDto;
use App\Application\Articles\UseCase\PostArticleUseCase;
use App\Application\Articles\Validation;
use App\Application\Authors\DataTransformer\Output\AuthorDataTransformer;
use App\Application\Authors\Dto\Output\AuthorDto;
use App\Application\Exceptions\BodyRequestException;
use App\Application\Exceptions\DtoValidationException;
use App\Tests\Aplication\Abstracts\AbstractPostTest;
use Psr\Log\LoggerInterface;

class ArticlePostTest extends AbstractPostTest
{
    protected PostArticleUseCase $postArticleUseCase;

    public function setUp(): void
    {
        parent::setUp();

        /** @var LoggerInterface $logger */
        $logger = $this->container->get(LoggerInterface::class);
        $this->postArticleUseCase = new PostArticleUseCase(
            articleInputDataTransformer: new InputArticleDataTransformer(
                articleRepository: $this->articleRepositoryMock,
                authorRepository: $this->authorRepositoryMock,
                authorDataTransformer: new AuthorDataTransformer(),
                logger: $logger
            ),
            articleOutputDataTransformer: new OutputArticleDataTransformer(new AuthorDataTransformer()),
            articleRepository: $this->articleRepositoryMock,
            validation: new Validation(),
            logger: $logger
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
        $this->expectException(DtoValidationException::class);
        $this->postArticleUseCase->post($request);
    }

    public function testRequestErrorAuthorNullPost(): void
    {
        $request = ['title' => null, 'body' => null, 'author' => null];
        $this->expectException(DtoValidationException::class);
        $this->postArticleUseCase->post($request);
    }

    public function testRequestErrorTitleAndBodyNullOrEmptyNullPost(): void
    {
        $request = ['title' => null, 'body' => '', 'author' => 'uuid'];
        $this->expectException(DtoValidationException::class);
        $this->postArticleUseCase->post($request);
    }

    public function testUserNotExist(): void
    {
        $request = ['title' => 'Title test', 'body' => 'Body test', 'author' => 'NO_EXIST_UUID'];
        $this->expectException(DtoValidationException::class);
        $this->postArticleUseCase->post($request);
    }
}
