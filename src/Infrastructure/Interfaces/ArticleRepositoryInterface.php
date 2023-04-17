<?php

namespace App\Infrastructure\Interfaces;

use App\Domain\Entity\Article;
use App\Domain\Entity\Author;

interface ArticleRepositoryInterface
{
    public function save(Article $entity, bool $persist = false, bool $flush = false): void;

    public function remove(Article $entity, bool $flush = false): void;

    public function create(Author $author): Article;

    /**
     * @return array<int, Article>
     */
    public function getAll(): array;

    public function getOne(string $uuid): ?Article;

    public function post(Article $article, bool $persist = false, bool $flush = false): void;
}
