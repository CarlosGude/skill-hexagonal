<?php

namespace App\Application\Articles\UseCase;

use App\Application\Abstracts\Interfaces\Output\DtoInterface;
use App\Application\Articles\DataTransformer\Input\ArticleDataTransformer as ArticleInputDataTransformer;
use App\Application\Articles\DataTransformer\Output\ArticleDataTransformer as ArticleOutputDataTransformer;
use App\Application\Articles\Dto\Input\ArticleDto;
use App\Application\Articles\Validation;
use App\Application\Exceptions\DtoValidationException;
use App\Application\Logger\ApplicationLogger;
use App\Infrastructure\Interfaces\ArticleRepositoryInterface;
use Psr\Log\LoggerInterface;

class PostArticleUseCase
{
    public function __construct(
        protected readonly ArticleInputDataTransformer $articleInputDataTransformer,
        protected readonly ArticleOutputDataTransformer $articleOutputDataTransformer,
        protected readonly ArticleRepositoryInterface $articleRepository,
        protected readonly LoggerInterface $logger,
        protected readonly Validation $validation
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
            $this->logger->error(ApplicationLogger::ERROR_ARTICLE_REQUEST_VALIDATION, ['errors' => $dto]);
            throw (new DtoValidationException())->setErrors($dto);
        }

        if (!empty($errors = $this->validation->validate($dto))) {
            $this->logger->error(ApplicationLogger::ERROR_ARTICLE_VALIDATION, ['errors' => $errors]);
            throw (new DtoValidationException())->setErrors($errors);
        }

        $article = $this->articleInputDataTransformer->dtoToEntity($dto);
        $this->articleRepository->post($article, $persist, $flush);

        return $this->articleOutputDataTransformer->transformFromEntity($article);
    }
}
