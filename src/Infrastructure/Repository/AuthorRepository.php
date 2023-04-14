<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\AbstractEntity;
use App\Domain\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function save(Author $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Author $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array <int, Author>
     */
    public function getAll(): array
    {
        return $this->getEntityManager()->getRepository(Author::class)->findAll();
    }

    public function getOne(string $uuid): ?AbstractEntity
    {
        return $this->getEntityManager()->getRepository(Author::class)->findOneBy(['uuid' => $uuid]);
    }
}
