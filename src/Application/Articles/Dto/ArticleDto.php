<?php

namespace App\Application\Articles\Dto;

use App\Application\Abstracts\Interfaces\DtoInterface;
use App\Application\Authors\Dto\AuthorDto;

class ArticleDto implements DtoInterface
{
    public function __construct(
        protected readonly ?string $uuid,
        protected readonly ?string $title,
        protected readonly ?string $body,
        protected readonly AuthorDto $author,
        protected readonly ?\DateTime $createdAt,
    ) {
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function getAuthor(): AuthorDto
    {
        return $this->author;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }
}
