<?php

namespace App\Application\Authors\DataTransformer\Output;

use App\Application\Abstracts\DataTransformer\Output\AbstractDataTransformer;
use App\Application\Authors\Dto\Output\AuthorDto;
use App\Domain\Entity\AbstractEntity;
use App\Domain\Entity\Article;
use App\Domain\Entity\Author;

class AuthorDataTransformer extends AbstractDataTransformer
{
    /**
     * @param Author $data
     */
    protected function getDto(AbstractEntity $data, bool $nested = true): AuthorDto
    {
        /** @var array<int, Article> $articles */
        $articles = $data->getArticles()->toArray();

        return new AuthorDto(
            uuid: $data->getUuid(),
            name: (string) $data->getName(),
            email: (string) $data->getEmail(),
            createdAt: $data->getCreatedAt(),
            articlesEntity: $articles
        );
    }
}
