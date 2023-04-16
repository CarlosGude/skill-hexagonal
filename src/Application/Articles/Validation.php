<?php

namespace App\Application\Articles;

use App\Application\Abstracts\AbstractValidator;
use App\Application\Abstracts\Interfaces\Input\DtoInterface;
use App\Application\Articles\Dto\Input\ArticleDto;
use App\Application\Authors\Dto\Output\AuthorDto;

class Validation extends AbstractValidator
{
    public const AUTHOR_NOT_FOUND = 'Author not found';

    /**
     * @param ArticleDto $dto
     *
     * @return array<string, string>>
     */
    public function validate(DtoInterface $dto): array
    {
        $errors = [];

        if (empty($dto->getTitle())) {
            $errors['title'] = parent::VALUE_NULL;
        }
        if (empty($dto->getBody())) {
            $errors['body'] = parent::VALUE_NULL;
        }

        if (!$dto->getAuthor() instanceof AuthorDto) {
            $errors['author'] = self::AUTHOR_NOT_FOUND;
        }

        return $errors;
    }
}
