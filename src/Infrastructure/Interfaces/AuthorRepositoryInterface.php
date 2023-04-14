<?php

namespace App\Infrastructure\Interfaces;

use App\Domain\Entity\Author;

interface AuthorRepositoryInterface
{
    public function save(Author $entity, bool $flush = false): void;

    public function remove(Author $entity, bool $flush = false): void;

    /**
     * @return array<int, Author>
     */
    public function getAll(): array;

    public function getOne(string $uuid): ?Author;
}
