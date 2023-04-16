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
use App\Domain\Entity\Article;
use App\Domain\Entity\Author;
use App\Infrastructure\Interfaces\AuthorRepositoryInterface;

class ArticleDataTransformer extends AbstractDataTransformer
{
    public function __construct(
        protected AuthorRepositoryInterface $authorRepository,
        protected AuthorDataTransformer $authorDataTransformer
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
            throw new \Exception();
        }

        /** @var Author $authorEntity */
        $authorEntity = $this->authorRepository->getOne($authorDto->getUuid());
        $article = new Article($authorEntity);
        $article->setTitle((string) $dto->getTitle());
        $article->setBody((string) $dto->getBody());

        return $article;
    }
}
