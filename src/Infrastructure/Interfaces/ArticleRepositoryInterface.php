<?php

namespace App\Infrastructure\Interfaces;

use App\Application\Articles\Dto\Input\ArticleDto;
use App\Domain\Entity\Article;

interface ArticleRepositoryInterface
{
    public function save(Article $entity, bool $flush = false): void;

    public function remove(Article $entity, bool $flush = false): void;

    /**
     * @return array<int, Article>
     */
    public function getAll(): array;

    public function getOne(string $uuid): ?Article;

    public function put(ArticleDto $dto, bool $flush = false): Article;
}
