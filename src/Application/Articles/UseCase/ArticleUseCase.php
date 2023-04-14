<?php

namespace App\Application\Articles\UseCase;

use App\Application\Articles\DataTransformer\ArticleDataTransformer;
use App\Infrastructure\Dto\ResponseDto;
use App\Infrastructure\Http\HttpCode;
use App\Infrastructure\Repository\ArticleRepository;

class ArticleUseCase
{
    public function __construct(
        protected readonly ArticleRepository $articleRepository,
        protected readonly ArticleDataTransformer $transformer
    ) {
    }

    public function get(?string $uuid = null): ResponseDto
    {
        $data = $uuid ? $this->articleRepository->getOne($uuid) : $this->articleRepository->getAll();

        return new ResponseDto(
            content: $this->transformer->transform($data),
            code: empty($data) ? HttpCode::HTTP_NOT_FOUND : HttpCode::HTTP_OK
        );
    }
}
