<?php

namespace App\Infrastructure\Interfaces;

use App\Domain\Entity\Article;

interface ArticleRepositoryInterface
{
    public function save(Article $entity, bool $persist = false, bool $flush = false): void;

    public function remove(Article $entity, bool $flush = false): void;

    /**
     * @return array<int, Article>
     */
    public function getAll(): array;

    public function getOne(string $uuid): ?Article;

    public function put(Article $article, bool $persist = false, bool $flush = false): void;
}
