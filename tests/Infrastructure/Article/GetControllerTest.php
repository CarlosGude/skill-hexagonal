<?php

namespace App\Tests\Infrastructure\Article;

use App\Application\Articles\DataTransformer\Output\ArticleDataTransformer;
use App\Application\Articles\UseCase\GetArticleUseCase;
use App\Application\Authors\DataTransformer\Output\AuthorDataTransformer;
use App\Infrastructure\Http\Articles\GetController;
use App\Infrastructure\Http\Articles\GetOneController;
use App\Infrastructure\Repository\MySQLArticleRepository;
use App\Infrastructure\Repository\MySQLAuthorRepository;
use App\Tests\Aplication\Article\ArticleGetTest;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GetControllerTest extends KernelTestCase
{
    protected MySQLAuthorRepository $authorRepositoryMock;
    protected GetController $getController;
    protected GetOneController $getOneController;
    protected GetArticleUseCase $getArticleUseCase;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $articles = ArticleGetTest::generateMockArticles();

        $articleRepository = $this->createMock(MySQLArticleRepository::class);

        // Mocks an array of user Response
        $articleRepository->expects($this->any())->method('getAll')->willReturn($articles);

        // Mocks User response
        $articleRepository->expects($this->any())->method('getOne')
            ->willReturnCallback(fn (string $value) => match (true) {
                'NO_EXIST_UUID' === $value => null,
                default => $articles[0]
            });

        /** @var GetController $getController */
        $getController = $container->get(GetController::class);

        /** @var GetOneController $getOneController */
        $getOneController = $container->get(GetOneController::class);

        $this->getController = $getController;
        $this->getOneController = $getOneController;

        $this->getArticleUseCase = new GetArticleUseCase(
            articleRepository: $articleRepository,
            transformer: new ArticleDataTransformer(new AuthorDataTransformer())
        );
    }

    public function testArrayOfArticle(): void
    {
        $response = $this->getController->get($this->getArticleUseCase);
        $array = json_decode((string) $response->getContent(), true);
        $this->assertIsArray($array);
        $this->assertGreaterThan(0, $array);
        $this->assertIsString($array[0]['title']);
        $this->assertIsString($array[0]['body']);
        $this->assertIsArray($array[0]['author']);
    }

    public function testArticle(): void
    {
        $response = $this->getOneController->getOne($this->getArticleUseCase, 'uuid');
        $array = json_decode((string) $response->getContent(), true);
        $this->assertIsArray($array);
        $this->assertIsString($array['title']);
        $this->assertIsString($array['body']);
        $this->assertIsArray($array['author']);
    }
}
