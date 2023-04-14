<?php

namespace App\Tests\Unitary\Article;

use App\Application\Articles\DataTransformer\ArticleDataTransformer;
use App\Application\Articles\Dto\ArticleDto;
use App\Application\Articles\UseCase\ArticleUseCase;
use App\Application\Authors\DataTransformer\AuthorDataTransformer;
use App\Domain\Entity\Article;
use App\Infrastructure\Dto\ResponseDto;
use App\Infrastructure\Repository\MySQLArticleRepository;
use App\Tests\Unitary\Author\AuthorGetTest;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ArticleGetTest extends KernelTestCase
{
    protected ArticleUseCase $authorsGetUseCase;

    /** @var array <int,Article> */
    protected array $articles = [];

    /**
     * @return array <int, Article>
     */
    public static function generateMockArticles(): array
    {
        $articles = [];
        foreach (AuthorGetTest::generateMockUsers() as $author) {
            for ($i = 0; $i <= rand(5, 10); ++$i) {
                $articles[] = (new Article($author))
                    ->setTitle('TEST ARTICLE '.$i)
                    ->setBody('TEST ARTICLE BODY')
                ;
            }
        }

        return $articles;
    }

    protected function setUp(): void
    {
        $this->articles = $this->generateMockArticles();
        $articleRepositoryMock = $this->createMock(MySQLArticleRepository::class);

        // Mocks an array of user Response
        $articleRepositoryMock->expects($this->any())->method('getAll')->willReturn($this->articles);

        // Mocks User response
        $articleRepositoryMock->expects($this->any())->method('getOne')
            ->willReturnCallback(fn (string $value) => match (true) {
                'NO_EXIST_UUID' === $value => null,
                default => $this->articles[array_rand($this->articles)]
            });

        $this->authorsGetUseCase = new ArticleUseCase(
            articleRepository: $articleRepositoryMock,
            transformer: new ArticleDataTransformer(new AuthorDataTransformer())
        );
    }

    public function testGet(): void
    {
        $response = $this->authorsGetUseCase->get();
        $this->assertInstanceOf(ResponseDto::class, $response);
        $this->assertEquals(200, $response->getCode());
        $this->assertNotEmpty($response->getContent());
        $this->assertIsArray($response->getContent());
        $this->assertInstanceOf(ArticleDto::class, $response->getContent()[0]);
    }

    public function testGetOne(): void
    {
        $response = $this->authorsGetUseCase->get('uuid');
        $this->assertInstanceOf(ResponseDto::class, $response);
        $this->assertEquals(200, $response->getCode());
        $this->assertNotEmpty($response->getContent());
        $this->assertIsNotArray($response->getContent());
        $this->assertInstanceOf(ArticleDto::class, $response->getContent());
    }

    public function testGetOneNoExist(): void
    {
        $response = $this->authorsGetUseCase->get('NO_EXIST_UUID');
        $this->assertInstanceOf(ResponseDto::class, $response);
        $this->assertEquals(404, $response->getCode());
        $this->assertEmpty($response->getContent());
    }
}
