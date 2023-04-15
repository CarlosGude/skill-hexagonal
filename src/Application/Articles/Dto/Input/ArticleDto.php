<?php

namespace App\Application\Articles\Dto\Input;

use App\Application\Abstracts\Interfaces\Input\DtoInterface;
use App\Application\Authors\Dto\Output\AuthorDto;

class ArticleDto implements DtoInterface
{
    public function __construct(
        protected readonly ?string $title,
        protected readonly ?string $body,
        protected readonly ?AuthorDto $author,
    ) {
    }

    public function getTitle():? string
    {
        return $this->title;
    }

    public function getBody():? string
    {
        return $this->body;
    }

    public function getAuthor():? AuthorDto
    {
        return $this->author;
    }
}
