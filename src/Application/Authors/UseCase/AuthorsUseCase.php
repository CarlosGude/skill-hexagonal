<?php

namespace App\Application\Authors\UseCase;

use App\Application\Abstracts\Interfaces\DtoInterface;
use App\Application\Authors\DataTransformer\AuthorDataTransformer;
use App\Application\Exceptions\AuthorNotFoundException;
use App\Domain\Entity\Author;
use App\Infrastructure\Interfaces\AuthorRepositoryInterface;

final class AuthorsUseCase
{
    public function __construct(
        protected readonly AuthorRepositoryInterface $authorRepository,
        protected readonly AuthorDataTransformer $transformer
    ) {
    }

    /**
     * @return array<int, DtoInterface>
     */
    public function getAll(): array
    {
        $data = $this->authorRepository->getAll();
        if (empty($data)) {
            throw new AuthorNotFoundException();
        }

        return $this->transformer->transformArray($data);
    }

    public function get(string $uuid): DtoInterface
    {
        /** @var Author $data */
        $data = $this->authorRepository->getOne($uuid);

        if (!$data instanceof Author) {
            throw new AuthorNotFoundException();
        }

        return $this->transformer->transformFromEntity($data);
    }
}
