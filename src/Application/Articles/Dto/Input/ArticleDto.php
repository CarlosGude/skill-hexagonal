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
      protected readonly ?AuthorDto $author= null,
    ){}

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }


    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return null|AuthorDto
     */
    public function getAuthor(): ?AuthorDto
    {
        return $this->author;
    }


}