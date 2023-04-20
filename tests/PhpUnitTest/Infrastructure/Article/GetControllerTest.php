<?php

namespace App\Tests\PhpUnitTest\Infrastructure\Article;

use App\Application\Articles\DataTransformer\Output\ArticleDataTransformer;
use App\Application\Articles\UseCase\GetArticleUseCase;
use App\Application\Authors\DataTransformer\Output\AuthorDataTransformer;
use App\Infrastructure\Http\Articles\GetController;
use App\Infrastructure\Http\Articles\GetOneController;
use App\Tests\PhpUnitTest\Infrastructure\AbstractTest;
use Psr\Log\LoggerInterface;

class GetControllerTest extends AbstractTest
{
    protected GetController $getController;
    protected GetOneController $getOneController;
    protected GetArticleUseCase $getArticleUseCase;

    protected function setUp(): void
    {
        parent::setUp();
        /** @var LoggerInterface $logger */
        $logger = $this->container->get(LoggerInterface::class);

        /** @var GetController $getController */
        $getController = $this->container->get(GetController::class);

        /** @var GetOneController $getOneController */
        $getOneController = $this->container->get(GetOneController::class);

        $this->getController = $getController;
        $this->getOneController = $getOneController;

        $this->getArticleUseCase = new GetArticleUseCase(
            articleRepository: $this->articleRepository,
            transformer: new ArticleDataTransformer(new AuthorDataTransformer()),
            logger: $logger
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
