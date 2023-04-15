<?php

namespace App\Infrastructure\Repository;

use App\Application\Articles\Dto\Input\ArticleDto;
use App\Domain\Entity\Article;
use App\Domain\Entity\Author;
use App\Infrastructure\Interfaces\ArticleRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MySQLArticleRepository extends ServiceEntityRepository implements ArticleRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function save(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array <int, Article>
     */
    public function getAll(): array
    {
        return $this->getEntityManager()->getRepository(Article::class)->findAll();
    }

    public function getOne(string $uuid): ?Article
    {
        return $this->getEntityManager()->getRepository(Article::class)->findOneBy(['uuid' => $uuid]);
    }

    /**
     * @throws \Exception
     */
    public function put(ArticleDto $dto, bool $flush = false): Article
    {
        $author = $dto->getAuthor();
        if (!$author || $author->getUuid()) {
            throw new \Exception(); // TODO: Exception
        }
        /** @var Author $author */
        $author = $this->getEntityManager()->getRepository(Author::class)->findOneBy([
            'uuid' => $author->getUuid(),
        ]);

        $article = new Article($author);
        $article->setTitle((string) $dto->getTitle())
            ->setBody((string) $dto->getBody())
        ;

        $this->save($article, $flush);

        return $article;
    }
}
