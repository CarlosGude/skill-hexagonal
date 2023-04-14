<?php

namespace App\Application\Articles\DataTransformer;

use App\Application\Abstracts\DataTransformer\AbstractDataTransformer;
use App\Application\Abstracts\Interfaces\DtoInterface;
use App\Application\Articles\Dto\Output\ArticleDto;
use App\Application\Authors\DataTransformer\AuthorDataTransformer;
use App\Domain\Entity\AbstractEntity;
use App\Domain\Entity\Article;

class ArticleDataTransformer extends AbstractDataTransformer
{
    public function __construct(protected readonly AuthorDataTransformer $authorDataTransformer)
    {
    }

    /**
     * @param Article $data
     */
    protected function getDto(AbstractEntity $data, bool $nested = false): DtoInterface
    {
        $authorDto = (!$nested) ? $this->authorDataTransformer->transformFromEntity($data->getAuthor()) : null;

        return new ArticleDto(
            uuid: $data->getUuid(),
            title: $data->getTitle(),
            body: $data->getBody(),
            author: $authorDto,
            createdAt: $data->getCreatedAt(),
        );
    }
}
