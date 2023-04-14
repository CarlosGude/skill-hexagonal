<?php

namespace App\Domain\Entity;

class Article extends AbstractEntity
{
    protected string $title;

    protected string $body;

    protected Author $author;

    public function __construct(Author $author)
    {
        parent::__construct();
        $this->author = $author;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Article
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): Article
    {
        $this->body = $body;

        return $this;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }
}
