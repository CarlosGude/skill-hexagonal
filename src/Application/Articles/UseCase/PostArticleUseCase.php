<?php

namespace App\Application\Articles\UseCase;

use App\Application\Abstracts\Interfaces\Output\DtoInterface;
use App\Application\Articles\DataTransformer\Input\ArticleDataTransformer as ArticleInputDataTransformer;
use App\Application\Articles\DataTransformer\Output\ArticleDataTransformer as ArticleOutputDataTransformer;
use App\Application\Articles\Dto\Input\ArticleDto;
use App\Application\Articles\Dto\Input\ArticleDto as ArticleInputDto;
use App\Application\Articles\Validation;
use App\Application\Exceptions\BodyRequestException;
use App\Application\Exceptions\DtoValidationException;
use App\Infrastructure\Interfaces\ArticleRepositoryInterface;

class PostArticleUseCase
{
    public function __construct(
        protected ArticleInputDataTransformer $articleInputDataTransformer,
        protected ArticleOutputDataTransformer $articleOutputDataTransformer,
        protected ArticleRepositoryInterface $articleRepository,
        protected Validation $validation
    ) {
    }

    /**
     * @param array<string, string|null> $request
     *
     * @throws DtoValidationException
     */
    public function post(array $request, bool $persist = false, bool $flush = false): DtoInterface
    {
        /** @var array<string, string>|ArticleDto $dto */
        $dto = $this->articleInputDataTransformer->requestToDto($request);
        if (is_array($dto)) {
            throw (new DtoValidationException())->setErrors($dto);
        }

        if (!empty($errors = $this->validation->validate($dto))) {
            throw (new DtoValidationException())->setErrors($errors);
        }

        $article = $this->articleInputDataTransformer->dtoToEntity($dto);
        $this->articleRepository->post($article, $persist, $flush);

        return $this->articleOutputDataTransformer->transformFromEntity($article);
    }
}
