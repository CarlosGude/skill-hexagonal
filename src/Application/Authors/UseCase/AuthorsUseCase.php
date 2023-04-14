<?php

namespace App\Application\Authors\UseCase;

use App\Application\Authors\DataTransformer\AuthorDataTransformer;
use App\Infrastructure\Dto\ResponseDto;
use App\Infrastructure\Http\HttpCode;
use App\Infrastructure\Repository\AuthorRepository;

final class AuthorsUseCase
{
    public function __construct(
        protected readonly AuthorRepository $userRepository,
        protected readonly AuthorDataTransformer $transformer
    ) {
    }

    public function get(?string $uuid = null): ResponseDto
    {
        $data = $uuid ? $this->userRepository->getOne($uuid) : $this->userRepository->getAll();

        return new ResponseDto(
            content: $this->transformer->transform($data),
            code: empty($data) ? HttpCode::HTTP_NOT_FOUND : HttpCode::HTTP_OK
        );
    }
}
