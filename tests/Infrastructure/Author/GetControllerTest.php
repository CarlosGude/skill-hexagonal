<?php

namespace App\Tests\Infrastructure\Author;

use App\Application\Authors\DataTransformer\Output\AuthorDataTransformer;
use App\Application\Authors\UseCase\GetAuthorsUseCase;
use App\Infrastructure\Http\Authors\GetController;
use App\Infrastructure\Http\Authors\GetOneController;
use App\Tests\Infrastructure\AbstractTest;

class GetControllerTest extends AbstractTest
{
    protected GetController $getController;
    protected GetOneController $getOneController;
    protected GetAuthorsUseCase $getAuthorUseCase;

    protected function setUp(): void
    {
        parent::setUp();
        /** @var GetController $getController */
        $getController = $this->container->get(GetController::class);

        /** @var GetOneController $getOneController */
        $getOneController = $this->container->get(GetOneController::class);

        $this->getController = $getController;
        $this->getOneController = $getOneController;

        $this->getAuthorUseCase = new GetAuthorsUseCase(
            authorRepository: $this->authorRepository,
            transformer: new AuthorDataTransformer()
        );
    }

    public function testArrayOfUser(): void
    {
        $response = $this->getController->get($this->getAuthorUseCase);
        $array = json_decode((string) $response->getContent(), true);
        $this->assertIsArray($array);
        $this->assertGreaterThan(0, $array);
        $this->assertIsString($array[0]['name']);
        $this->assertIsString($array[0]['email']);
        $this->assertIsArray($array[0]['articles']);
    }

    public function testSingleUser(): void
    {
        $response = $this->getOneController->getOne($this->getAuthorUseCase, 'uuid');
        $array = json_decode((string) $response->getContent(), true);
        $this->assertIsArray($array);
        $this->assertIsString($array['name']);
        $this->assertIsString($array['email']);
        $this->assertIsArray($array['articles']);
    }
}
