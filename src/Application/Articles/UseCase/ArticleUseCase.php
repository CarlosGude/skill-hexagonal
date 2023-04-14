<?php

namespace App\Application\Articles\UseCase;

use App\Application\Abstracts\Interfaces\DtoInterface;
use App\Application\Articles\DataTransformer\ArticleDataTransformer;
use App\Application\Exceptions\ArticleNotFoundException;
use App\Domain\Entity\Article;
use App\Infrastructure\Interfaces\ArticleRepositoryInterface;

class ArticleUseCase
{
    public function __construct(
        protected readonly ArticleRepositoryInterface $articleRepository,
        protected readonly ArticleDataTransformer $transformer
    ) {
    }

    /**
     * @return array<int, DtoInterface>
     *
     * @throws ArticleNotFoundException
     */
    public function getAll(): array
    {
        $data = $this->articleRepository->getAll();
        if (empty($data)) {
            throw new ArticleNotFoundException();
        }

        return $this->transformer->transformArray($data);
    }

    /**
     * @throws ArticleNotFoundException
     */
    public function get(string $uuid): DtoInterface
    {
        /** @var Article $data */
        $data = $this->articleRepository->getOne($uuid);

        if (!$data instanceof Article) {
            throw new ArticleNotFoundException();
        }

        return $this->transformer->transformFromEntity($data);
    }
}
