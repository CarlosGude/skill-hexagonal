<?php

namespace App\Tests\Unitary\Article;

use App\Application\Articles\DataTransformer\Output\ArticleDataTransformer;
use App\Application\Articles\Dto\Output\ArticleDto;
use App\Application\Articles\UseCase\GetArticleUseCase;
use App\Application\Authors\DataTransformer\Output\AuthorDataTransformer;
use App\Application\Authors\Dto\Output\AuthorDto;
use App\Application\Exceptions\ArticleNotFoundException;
use App\Domain\Entity\Article;
use App\Infrastructure\Repository\MySQLArticleRepository;
use App\Tests\Unitary\Author\AuthorGetTest;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ArticleGetTest extends KernelTestCase
{
    protected GetArticleUseCase $articleUseCase;

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

        $this->articleUseCase = new GetArticleUseCase(
            articleRepository: $articleRepositoryMock,
            transformer: new ArticleDataTransformer(new AuthorDataTransformer())
        );
    }

    /**
     * @throws ArticleNotFoundException
     */
    public function testGet(): void
    {
        $articles = $this->articleUseCase->getAll();
        $this->assertNotEmpty($articles);
        $this->assertIsArray($articles);
        $this->assertInstanceOf(ArticleDto::class, $articles[0]);
        $this->assertInstanceOf(AuthorDto::class, $articles[0]->getAuthor());
    }

    /**
     * @throws ArticleNotFoundException
     */
    public function testGetOne(): void
    {
        $article = $this->articleUseCase->get('uuid');
        $this->assertIsNotArray($article);
        $this->assertInstanceOf(ArticleDto::class, $article);
        $this->assertInstanceOf(AuthorDto::class, $article->getAuthor());
    }

    public function testGetOneNoExist(): void
    {
        $this->expectException(ArticleNotFoundException::class);
        $this->articleUseCase->get('NO_EXIST_UUID');
    }
}
