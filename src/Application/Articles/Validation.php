<?php

namespace App\Application\Articles;

use App\Application\Abstracts\AbstractValidator;
use App\Application\Abstracts\Interfaces\Input\DtoInterface;
use App\Application\Articles\Dto\Input\ArticleDto;
use App\Application\Authors\Dto\Output\AuthorDto;

class Validation extends AbstractValidator
{
    public const AUTHOR_DATA_NOT_VALID = 'The data sent in author must be implements AuthorDto class.';
    public const INDEX_NOT_VALID = 'not_valid';

    /**
     * @param ArticleDto $dto
     *
     * @return array<string, array<string, string>>
     */
    public function validate(DtoInterface $dto): array
    {
        $errors = [];

        if (empty($dto->getTitle())) {
            $errors['title'][parent::INDEX_NULL] = parent::VALUE_NULL;
        }
        if (empty($dto->getBody())) {
            $errors['body'][parent::INDEX_NULL] = parent::VALUE_NULL;
        }

        if (!is_string($dto->getTitle())) {
            $errors['title'][self::INDEX_NOT_STRING] = parent::VALUE_NOT_STRING;
        }
        if (!is_string($dto->getBody())) {
            $errors['body'][self::INDEX_NOT_STRING] = parent::VALUE_NOT_STRING;
        }

        if (!$dto->getAuthor() instanceof AuthorDto) {
            $errors['author'][self::INDEX_NOT_VALID] = self::AUTHOR_DATA_NOT_VALID;
        }

        return $errors;
    }
}
