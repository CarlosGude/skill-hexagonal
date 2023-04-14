<?php

namespace App\Tests\Unitary\Author;

use App\Application\Articles\Dto\Output\ArticleDto;
use App\Application\Authors\DataTransformer\AuthorDataTransformer;
use App\Application\Authors\Dto\AuthorDto;
use App\Application\Authors\UseCase\AuthorsUseCase;
use App\Application\Exceptions\AuthorNotFoundException;
use App\Domain\Entity\Article;
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
            $author = (new Author())
                ->setName('User name '.$i)
                ->setEmail("user$i@email.com")
            ;

            for ($i = 0; $i <= rand(1, 10); ++$i) {
                $author->addArticle(
                    (new Article($author))
                        ->setTitle('Title '.$i)
                        ->setBody('Body '.$i)
                );
            }

            $authors[] = $author;
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
                default => $this->authors[0]
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
        $author = $authors[0];
        $this->assertInstanceOf(AuthorDto::class, $author);
        $this->assertIsArray($author->getArticles());
        $this->assertNotEmpty($author->getArticles());
        $this->assertInstanceOf(ArticleDto::class, $author->getArticles()[0]);
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
        $this->assertIsArray($author->getArticles());
        $this->assertNotEmpty($author->getArticles());
        $this->assertInstanceOf(ArticleDto::class, $author->getArticles()[0]);
    }

    public function testGetOneNoExist(): void
    {
        $this->expectException(AuthorNotFoundException::class);
        $this->authorsGetUseCase->get('NO_EXIST_UUID');
    }
}
