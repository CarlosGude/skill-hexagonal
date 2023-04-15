<?php

namespace App\Application\Articles;

use App\Application\Abstracts\AbstractValidator;
use App\Application\Abstracts\Interfaces\Input\DtoInterface;
use App\Application\Articles\Dto\Input\ArticleDto;
use App\Application\Authors\Dto\Output\AuthorDto;

class Validation extends AbstractValidator
{
    protected const AUTHOR_DATA_NOT_VALID = 'The data sent in author must be implements AuthorDto class.';


    /**
     * @param ArticleDto $dto
     * @return array<string,string>
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
        if (empty($dto->getBody())) {
            $errors['author'] = parent::VALUE_NULL;
        }

        if (!is_string($dto->getTitle())) {
            $errors['title'] = parent::VALUE_NOT_STRING;
        }
        if (!is_string($dto->getBody())) {
            $errors['body'] = parent::VALUE_NOT_STRING;
        }

        if (!$dto->getAuthor() instanceof AuthorDto) {
            $errors['author'] = self::AUTHOR_DATA_NOT_VALID;
        }

        return $errors;
    }
}
