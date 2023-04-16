<?php

namespace App\Application\Articles\DataTransformer\Output;

use App\Application\Abstracts\DataTransformer\Output\AbstractDataTransformer;
use App\Application\Abstracts\Interfaces\Output\DtoInterface;
use App\Application\Articles\Dto\Output\ArticleDto;
use App\Application\Authors\DataTransformer\Output\AuthorDataTransformer;
use App\Application\Authors\Dto\Output\AuthorDto;
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
        /** @var Article|AbstractEntity $author */
        $author = $data->getAuthor();
        /** @var AuthorDto|null $authorDto */
        $authorDto = (!$nested) ? $this->authorDataTransformer->transformFromEntity($author) : null;

        return new ArticleDto(
            uuid: $data->getUuid(),
            title: (string) $data->getTitle(),
            body: (string) $data->getBody(),
            author: $authorDto,
            createdAt: $data->getCreatedAt(),
        );
    }
}
