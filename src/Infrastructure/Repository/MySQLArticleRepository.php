<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Article;
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
}
