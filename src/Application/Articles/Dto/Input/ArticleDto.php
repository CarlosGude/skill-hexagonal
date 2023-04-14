<?php

namespace App\Application\Articles\Dto\Input;

use App\Application\Abstracts\Interfaces\Input\DtoInterface;
use App\Application\Authors\Dto\AuthorDto;

class ArticleDto implements DtoInterface
{
    public function __construct(
        protected readonly string $uuid,
        protected readonly string $title,
        protected readonly string $body,
        protected readonly ?AuthorDto $author = null,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getAuthor(): ?AuthorDto
    {
        return $this->author;
    }
}
