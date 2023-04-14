<?php

namespace App\Tests\Unitary\Author;

use App\Application\Authors\DataTransformer\AuthorDataTransformer;
use App\Application\Authors\Dto\AuthorDto;
use App\Application\Authors\UseCase\AuthorsUseCase;
use App\Application\Exceptions\AuthorNotFoundException;
use App\Domain\Entity\Author;
use App\Infrastructure\Repository\MySQLAuthorRepository;
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
        $userRepositoryMock = $this->createMock(MySQLAuthorRepository::class);

        // Mocks an array of user Response
        $userRepositoryMock->expects($this->any())->method('getAll')->willReturn($this->authors);

        // Mocks User response
        $userRepositoryMock->expects($this->any())->method('getOne')
            ->willReturnCallback(fn (string $value) => match (true) {
                'NO_EXIST_UUID' === $value => null,
                default => (new Author())->setName('TEST')->setEmail('test@email.com')
            });

        $this->authorsGetUseCase = new AuthorsUseCase(
            authorRepository: $userRepositoryMock,
            transformer: new AuthorDataTransformer()
        );
    }

    /**
     * @throws AuthorNotFoundException
     */
    public function testGet(): void
    {
        $authors = $this->authorsGetUseCase->getAll();
        $this->assertNotEmpty($authors);
        $this->assertIsArray($authors);
        $this->assertInstanceOf(AuthorDto::class, $authors[0]);
    }

    /**
     * @throws AuthorNotFoundException
     */
    public function testGetOne(): void
    {
        $author = $this->authorsGetUseCase->get('uuid');
        $this->assertNotEmpty($author);
        $this->assertIsNotArray($author);
        $this->assertInstanceOf(AuthorDto::class, $author);
    }

    public function testGetOneNoExist(): void
    {
        $this->expectException(AuthorNotFoundException::class);
        $this->authorsGetUseCase->get('NO_EXIST_UUID');
    }
}
