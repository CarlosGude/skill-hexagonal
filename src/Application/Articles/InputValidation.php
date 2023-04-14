<?php

namespace App\Application\Articles;

use App\Application\Articles\Dto\Input\ArticleDto;

class InputValidation
{
    /**
     * @return <int, string>array
     */
    public function validate(ArticleDto $articleDto): array
    {
        $errors = [];
        if (empty($articleDto->getTitle())) {
            $errors[] = ['filed' => 'title', 'message' => 'this value cant be empty'];
        }

        if (empty($articleDto->getBody())) {
            $errors[] = ['filed' => 'title', 'message' => 'this value cant be empty'];
        }

        if (empty($articleDto->getAuthor())) {
            $errors[] = ['filed' => 'author', 'message' => 'this value cant be empty'];
        }

        return $errors;
    }
}
