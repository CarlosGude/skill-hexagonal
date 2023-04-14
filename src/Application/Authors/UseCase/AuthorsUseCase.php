<?php

namespace App\Application\Authors\UseCase;

use App\Application\Abstracts\Interfaces\DtoInterface;
use App\Application\Authors\DataTransformer\AuthorDataTransformer;
use App\Application\Authors\Dto\AuthorDto;
use App\Application\Exceptions\AuthorNotFoundException;
use App\Domain\Entity\Author;
use App\Infrastructure\Dto\ResponseDto;
use App\Infrastructure\Http\HttpCode;
use App\Infrastructure\Interfaces\ArticleRepositoryInterface;
use App\Infrastructure\Interfaces\AuthorRepositoryInterface;
use App\Infrastructure\Repository\MySQLAuthorRepository;

final class AuthorsUseCase
{
    public function __construct(
        protected readonly AuthorRepositoryInterface $authorRepository,
        protected readonly AuthorDataTransformer     $transformer
    ) {
    }

    /**
     * @return array<int, DtoInterface>
     */
    public function getAll(): array
    {
        $data = $this->authorRepository->getAll();
        if (empty($data)){
            throw new AuthorNotFoundException();
        }

        return $this->transformer->transformArray($data);
    }

    public function get(string $uuid): DtoInterface
    {
        /** @var Author $data */
        $data = $this->authorRepository->getOne($uuid);

        if (!$data instanceof Author){
            throw new AuthorNotFoundException();
        }

        return $this->transformer->transformFromEntity($data);
    }
}
