<?php

namespace App\Tests\Unitary\Author;

use App\Application\Authors\DataTransformer\AuthorDataTransformer;
use App\Application\Authors\Dto\AuthorDto;
use App\Application\Authors\UseCase\AuthorsUseCase;
use App\Domain\Entity\Author;
use App\Infrastructure\Dto\ResponseDto;
use App\Infrastructure\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AuthorGetTest extends KernelTestCase
{
    protected AuthorsUseCase $authorsGetUseCase;

    /** @var array <int,Author> */
    protected array $authors = [];

    /**
     * @return array <int, Author>
     */
    public static function generateMockUsers(): array
    {
        $authors = [];
        for ($i = 0; $i <= rand(5, 10); ++$i) {
            $authors[] = (new Author())
                ->setName('User name '.$i)
                ->setEmail("user$i@email.com")
            ;
        }

        return $authors;
    }

    protected function setUp(): void
    {
        $this->authors = $this->generateMockUsers();
        $userRepositoryMock = $this->createMock(AuthorRepository::class);

        // Mocks an array of user Response
        $userRepositoryMock->expects($this->any())->method('getAll')->willReturn($this->authors);

        // Mocks User response
        $userRepositoryMock->expects($this->any())->method('getOne')
            ->willReturnCallback(fn (string $value) => match (true) {
                'NO_EXIST_UUID' === $value => null,
                default => (new Author())->setName('TEST')->setEmail('test@email.com')
            });

        $this->authorsGetUseCase = new AuthorsUseCase(
            userRepository: $userRepositoryMock,
            transformer: new AuthorDataTransformer()
        );
    }

    public function testGet(): void
    {
        $response = $this->authorsGetUseCase->get();
        $this->assertInstanceOf(ResponseDto::class, $response);
        $this->assertEquals(200, $response->getCode());
        $this->assertNotEmpty($response->getContent());
        $this->assertIsArray($response->getContent());
        $this->assertInstanceOf(AuthorDto::class, $response->getContent()[0]);
    }

    public function testGetOne(): void
    {
        $response = $this->authorsGetUseCase->get('uuid');
        $this->assertInstanceOf(ResponseDto::class, $response);
        $this->assertEquals(200, $response->getCode());
        $this->assertNotEmpty($response->getContent());
        $this->assertIsNotArray($response->getContent());
        $this->assertInstanceOf(AuthorDto::class, $response->getContent());
    }

    public function testGetOneNoExist(): void
    {
        $response = $this->authorsGetUseCase->get('NO_EXIST_UUID');
        $this->assertInstanceOf(ResponseDto::class, $response);
        $this->assertEquals(404, $response->getCode());
        $this->assertEmpty($response->getContent());
    }
}
