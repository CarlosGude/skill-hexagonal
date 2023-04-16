<?php

namespace App\Application\Authors\UseCase;

use App\Application\Abstracts\Interfaces\Output\DtoInterface;
use App\Application\Authors\DataTransformer\Output\AuthorDataTransformer;
use App\Application\Exceptions\AuthorNotFoundException;
use App\Domain\Entity\Author;
use App\Infrastructure\Interfaces\AuthorRepositoryInterface;

class GetAuthorsUseCase
{
    public function __construct(
        protected readonly AuthorRepositoryInterface $authorRepository,
        protected readonly AuthorDataTransformer $transformer
    ) {
    }

    /**
     * @return array<int, DtoInterface>
     *
     * @throws AuthorNotFoundException
     */
    public function getAll(): array
    {
        $data = $this->authorRepository->getAll();
        if (empty($data)) {
            throw new AuthorNotFoundException();
        }

        return $this->transformer->transformArray($data);
    }

    /**
     * @throws AuthorNotFoundException
     */
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
