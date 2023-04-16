<?php

namespace App\Tests\Infrastructure;

use App\Application\Authors\DataTransformer\Output\AuthorDataTransformer;
use App\Application\Authors\UseCase\GetAuthorsUseCase;
use App\Infrastructure\Http\Authors\GetController;
use App\Infrastructure\Http\Authors\GetOneController;
use App\Infrastructure\Repository\MySQLAuthorRepository;
use App\Tests\Aplication\Author\AuthorGetTest;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GetAuthorControllerTest extends KernelTestCase
{
    protected MySQLAuthorRepository $authorRepositoryMock;
    protected GetController $getController;
    protected GetOneController $getOneController;
    protected GetAuthorsUseCase $getAuthorUseCase;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $authors = AuthorGetTest::generateMockUsers();

        $authorRepositoryMock = $this->createMock(MySQLAuthorRepository::class);

        // Mocks an array of user Response
        $authorRepositoryMock->expects($this->any())->method('getAll')->willReturn($authors);

        // Mocks User response
        $authorRepositoryMock->expects($this->any())->method('getOne')
            ->willReturnCallback(fn (string $value) => match (true) {
                'NO_EXIST_UUID' === $value => null,
                default => $authors[0]
            });

        /** @var GetController $getController */
        $getController = $container->get(GetController::class);

        /** @var GetOneController $getOneController */
        $getOneController = $container->get(GetOneController::class);

        $this->getController = $getController;
        $this->getOneController = $getOneController;

        $this->getAuthorUseCase = new GetAuthorsUseCase(
            authorRepository: $authorRepositoryMock, transformer: new AuthorDataTransformer()
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
