<?php

namespace App\Application\Articles\UseCase;

use App\Application\Abstracts\Interfaces\Output\DtoInterface;
use App\Application\Articles\DataTransformer\Output\ArticleDataTransformer;
use App\Application\Exceptions\ArticleNotFoundException;
use App\Application\Logger\ApplicationLogger;
use App\Domain\Entity\Article;
use App\Infrastructure\Interfaces\ArticleRepositoryInterface;
use Psr\Log\LoggerInterface;

final class GetArticleUseCase
{
    public function __construct(
        protected readonly ArticleRepositoryInterface $articleRepository,
        protected readonly ArticleDataTransformer $transformer,
        protected readonly LoggerInterface $logger
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
            $this->logger->error(ApplicationLogger::ERROR_ARTICLE_NOT_FOUND);
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
            $this->logger->error(ApplicationLogger::ERROR_ARTICLE_NOT_FOUND, ['uuid' => $uuid]);
            throw new ArticleNotFoundException();
        }

        return $this->transformer->transformFromEntity($data);
    }
}
