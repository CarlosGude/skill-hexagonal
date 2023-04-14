<?php

namespace App\Application\Authors\DataTransformer;

use App\Application\Abstracts\DataTransformer\AbstractDataTransformer;
use App\Application\Authors\Dto\AuthorDto;
use App\Domain\Entity\AbstractEntity;
use App\Domain\Entity\Author;

class AuthorDataTransformer extends AbstractDataTransformer
{
    /**
     * @param Author $data
     */
    protected function getDto(AbstractEntity $data, bool $nested = true): AuthorDto
    {
        return new AuthorDto(
            uuid: $data->getUuid(),
            name: $data->getName(),
            email: $data->getEmail(),
            createdAt: $data->getCreatedAt(),
            articlesEntity: $data->getArticles()
        );
    }
}
