<?php

namespace App\Application\Articles\DataTransformer\Input;

use App\Application\Abstracts\DataTransformer\Input\AbstractDataTransformer;
use App\Application\Abstracts\Interfaces\Input\DtoInterface;
use App\Application\Articles\Dto\Input\ArticleDto;
use App\Application\Articles\Validation;
use App\Application\Authors\DataTransformer\Output\AuthorDataTransformer;
use App\Application\Authors\Dto\Output\AuthorDto;
use App\Application\Exceptions\AuthorNotFoundException;
use App\Application\Exceptions\BodyRequestException;
use App\Application\Exceptions\DtoException;
use App\Application\Logger\ApplicationLogger;
use App\Domain\Entity\Article;
use App\Domain\Entity\Author;
use App\Infrastructure\Interfaces\AuthorRepositoryInterface;
use Psr\Log\LoggerInterface;

class ArticleDataTransformer extends AbstractDataTransformer
{
    public function __construct(
        protected readonly AuthorRepositoryInterface $authorRepository,
        protected readonly AuthorDataTransformer $authorDataTransformer,
        protected readonly LoggerInterface $logger
    ) {
    }

    /**
     * @param array<string,string|null> $request
     *
     * @throws BodyRequestException
     * @throws AuthorNotFoundException
     */
    public function requestToDto(array $request): array|DtoInterface
    {
        if (array_keys($request) != ['title', 'body', 'author']) {
            $this->logger->error(ApplicationLogger::ERROR_BODY_REQUEST, ['request' => $request]);
            throw new BodyRequestException();
        }

        if (!$request['author']) {
            $errors['author'] = Validation::VALUE_NULL;

            return $errors;
        }

        /** @var Author|null $authorEntity */
        $authorEntity = $this->authorRepository->getOne($request['author']);

        if (!$authorEntity) {
            $errors['author'] = Validation::AUTHOR_NOT_FOUND;
            $this->logger->error(ApplicationLogger::ERROR_AUTHOR_NOT_FOUND, ['author' => $request['author']]);

            return $errors;
        }

        /** @var AuthorDto $author */
        $author = $this->authorDataTransformer->transformFromEntity($authorEntity);

        return new ArticleDto(
            title: $request['title'],
            body: $request['body'],
            author: $author
        );
    }

    public function dtoToEntity(ArticleDto $dto): Article
    {
        $authorDto = $dto->getAuthor();

        if (!$authorDto) {
            $this->logger->error(ApplicationLogger::ERROR_AUTHOR_NOT_FOUND);
            throw new DtoException();
        }

        /** @var Author $authorEntity */
        $authorEntity = $this->authorRepository->getOne($authorDto->getUuid());
        $article = new Article($authorEntity);
        $article->setTitle((string) $dto->getTitle());
        $article->setBody((string) $dto->getBody());

        return $article;
    }
}
