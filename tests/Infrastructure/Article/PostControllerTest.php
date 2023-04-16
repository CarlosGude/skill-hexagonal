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
use App\Tests\Aplication\Abstracts\AbstractPostTest;
use App\Tests\Aplication\Author\AuthorGetTest;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class PostControllerTest extends KernelTestCase
{
    protected MySQLAuthorRepository $authorRepositoryMock;

    protected PostController $postController;
    protected PostArticleUseCase $postArticleUseCase;

    protected ContainerInterface $container;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $articles = AbstractPostTest::generateMockArticles();
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

        $this->postArticleUseCase = new PostArticleUseCase(
            articleInputDataTransformer: new InputArticleDataTransformer($this->authorRepositoryMock, new AuthorDataTransformer()),
            articleOutputDataTransformer: new OutputArticleDataTransformer(new AuthorDataTransformer()),
            articleRepository: $this->articleRepositoryMock,
            validation: new Validation()
        );

        $postController = $container->get(PostController::class);
        $this->postController = $postController;
    }

    public function testPost(): void
    {
        $request = $this->createMock(Request::class);

        $request->expects($this->any())->method('toArray')
            ->willReturn(['title' => 'TITLE', 'body' => 'BODY', 'author' => 'uuid']);

        $response = $this->postController->post($this->postArticleUseCase,$request,false,false);
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

        $response = $this->postController->post($this->postArticleUseCase,$request,false,false);
        $this->assertEquals(HttpCode::HTTP_BAD_REQUEST,$response->getStatusCode());
        $array = json_decode((string) $response->getContent(), true);
        $this->assertIsArray($array);
        $this->assertEquals(AbstractValidator::VALUE_NULL,$array['title']);
    }

    public function testErrorBodyNull(): void
    {
        $request = $this->createMock(Request::class);

        $request->expects($this->any())->method('toArray')
            ->willReturn(['title' => 'TITLE', 'body' => null, 'author' => 'uuid']);

        $response = $this->postController->post($this->postArticleUseCase,$request,false,false);
        $this->assertEquals(HttpCode::HTTP_BAD_REQUEST,$response->getStatusCode());
        $array = json_decode((string) $response->getContent(), true);
        $this->assertIsArray($array);
        $this->assertEquals(AbstractValidator::VALUE_NULL,$array['body']);
    }

    public function testErrorAuthorNull(): void
    {
        $request = $this->createMock(Request::class);

        $request->expects($this->any())->method('toArray')
            ->willReturn(['title' => 'TITLE', 'body' => 'BODY', 'author' => 'NO_EXIST_UUID']);

        $response = $this->postController->post($this->postArticleUseCase,$request,false,false);
        $this->assertEquals(HttpCode::HTTP_BAD_REQUEST,$response->getStatusCode());
        $array = json_decode((string) $response->getContent(), true);
        $this->assertIsArray($array);
        $this->assertEquals(Validation::AUTHOR_NOT_FOUND,$array['author']);
    }
}
