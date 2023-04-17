<?php

namespace App\Tests\Infrastructure\Article;

use App\Application\Abstracts\AbstractValidator;
use App\Application\Articles\DataTransformer\Input\ArticleDataTransformer as InputArticleDataTransformer;
use App\Application\Articles\DataTransformer\Output\ArticleDataTransformer as OutputArticleDataTransformer;
use App\Application\Articles\UseCase\PostArticleUseCase;
use App\Application\Articles\Validation;
use App\Application\Authors\DataTransformer\Output\AuthorDataTransformer;
use App\Infrastructure\Http\Articles\PostController;
use App\Infrastructure\Http\HttpCode;
use App\Infrastructure\Repository\MySQLArticleRepository;
use App\Infrastructure\Repository\MySQLAuthorRepository;
use App\Tests\Infrastructure\AbstractTest;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class PostControllerTest extends AbstractTest
{
    protected MySQLAuthorRepository $authorRepositoryMock;
    protected MySQLArticleRepository $articleRepositoryMock;
    protected PostController $postController;
    protected PostArticleUseCase $postArticleUseCase;

    protected ContainerInterface $container;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @var LoggerInterface $logger */
        $logger = $this->container->get(LoggerInterface::class);

        $this->postArticleUseCase = new PostArticleUseCase(
            articleInputDataTransformer: new InputArticleDataTransformer(
                articleRepository: $this->articleRepository,
                authorRepository: $this->authorRepository,
                authorDataTransformer: new AuthorDataTransformer(),
                logger: $logger
            ),
            articleOutputDataTransformer: new OutputArticleDataTransformer(new AuthorDataTransformer()),
            articleRepository: $this->articleRepository,
            validation: new Validation(),
            logger: $logger
        );

        /** @var PostController $postController */
        $postController = $this->container->get(PostController::class);
        $this->postController = $postController;
    }

    public function testPost(): void
    {
        $request = $this->createMock(Request::class);

        $request->expects($this->any())->method('toArray')
            ->willReturn(['title' => 'TITLE', 'body' => 'BODY', 'author' => 'uuid']);

        $response = $this->postController->post($this->postArticleUseCase, $request, false, false);
        $array = json_decode((string) $response->getContent(), true);
        $this->assertIsArray($array);
        $this->assertIsString($array['title']);
        $this->assertIsString($array['body']);
        $this->assertIsArray($array['author']);
    }

    public function testErrorTitleNull(): void
    {
        $request = $this->createMock(Request::class);

        $request->expects($this->any())->method('toArray')
            ->willReturn(['title' => null, 'body' => 'BODY', 'author' => 'uuid']);

        $response = $this->postController->post($this->postArticleUseCase, $request, false, false);
        $this->assertEquals(HttpCode::HTTP_BAD_REQUEST, $response->getStatusCode());
        $array = json_decode((string) $response->getContent(), true);
        $this->assertIsArray($array);
        $this->assertEquals(AbstractValidator::VALUE_NULL, $array['title']);
    }

    public function testErrorBodyNull(): void
    {
        $request = $this->createMock(Request::class);

        $request->expects($this->any())->method('toArray')
            ->willReturn(['title' => 'TITLE', 'body' => null, 'author' => 'uuid']);

        $response = $this->postController->post($this->postArticleUseCase, $request, false, false);
        $this->assertEquals(HttpCode::HTTP_BAD_REQUEST, $response->getStatusCode());
        $array = json_decode((string) $response->getContent(), true);
        $this->assertIsArray($array);
        $this->assertEquals(AbstractValidator::VALUE_NULL, $array['body']);
    }

    public function testErrorAuthorNull(): void
    {
        $request = $this->createMock(Request::class);

        $request->expects($this->any())->method('toArray')
            ->willReturn(['title' => 'TITLE', 'body' => 'BODY', 'author' => 'NO_EXIST_UUID']);

        $response = $this->postController->post($this->postArticleUseCase, $request, false, false);
        $this->assertEquals(HttpCode::HTTP_BAD_REQUEST, $response->getStatusCode());
        $array = json_decode((string) $response->getContent(), true);
        $this->assertIsArray($array);
        $this->assertEquals(Validation::AUTHOR_NOT_FOUND, $array['author']);
    }
}
