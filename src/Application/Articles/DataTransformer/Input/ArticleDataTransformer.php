<?php

namespace App\Application\Articles\DataTransformer\Input;

use App\Application\Abstracts\DataTransformer\Input\AbstractDataTransformer;
use App\Application\Abstracts\Interfaces\Input\DtoInterface;
use App\Application\Articles\Dto\Input\ArticleDto;
use App\Application\Authors\DataTransformer\Output\AuthorDataTransformer;
use App\Application\Authors\Dto\Output\AuthorDto;
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
     * @param array<string,string> $request
     *
     * @throws \Exception
     */
    public function requestToDto(array $request): DtoInterface
    {
        if (array_keys($request) == ['title', 'body', 'author']) {
            throw new \Exception(); // TODO: Custom Exception
        }

        /** @var Author $authorEntity */
        $authorEntity = $this->authorRepository->getOne($request['author']);

        /** @var AuthorDto $author */
        $author = $this->authorDataTransformer->transformFromEntity($authorEntity);

        return new ArticleDto(
            title: $request['title'],
            body: $request['body'],
            author: $author
        );
    }
}
