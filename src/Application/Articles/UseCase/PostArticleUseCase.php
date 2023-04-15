<?php

namespace App\Application\Articles\UseCase;

use App\Application\Abstracts\Interfaces\Output\DtoInterface;
use App\Application\Articles\DataTransformer\Input\ArticleDataTransformer as ArticleInputDataTransformer;
use App\Application\Articles\DataTransformer\Output\ArticleDataTransformer as ArticleOutputDataTransformer;
use App\Application\Articles\Dto\Input\ArticleDto as ArticleInputDto;
use App\Application\Articles\Validation;
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
     * @param array<string,string> $request
     *
     * @throws \Exception
     */
    public function post(array $request, bool $flush = false): DtoInterface
    {
        /** @var ArticleInputDto $dto */
        $dto = $this->articleInputDataTransformer->requestToDto($request);

        if (!empty($errors = $this->validation->validate($dto))) {
            throw new \Exception((string) json_encode($errors)); // TODO: CUSTOM EXCEPTION
        }

        $article = $this->articleInputDataTransformer->dtoToEntity($dto);
        $this->articleRepository->put($article, $flush);

        return $this->articleOutputDataTransformer->transformFromEntity($article);
    }
}
