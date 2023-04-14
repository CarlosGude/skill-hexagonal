<?php

namespace App\Application\Authors\Dto;

use App\Application\Abstracts\Interfaces\DtoInterface;
use App\Application\Articles\Dto\Output\ArticleDto;
use App\Domain\Entity\Article;

class AuthorDto implements DtoInterface
{
    protected array $articles = [];
    public function __construct(
        protected readonly ?string    $uuid,
        protected readonly ?string    $name,
        protected readonly ?string    $email,
        protected readonly ?\DateTime $createdAt,
        array               $articlesEntity,

    ) {
        foreach ($articlesEntity as $article){
            $this->addArticle($article);
        }
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    protected function addArticle(Article $article): void
    {
        $this->articles[] = new ArticleDto(
            uuid: $article->getUuid(),
            title: $article->getTitle(),
            body: $article->getBody(),
            createdAt: $article->getCreatedAt(),
        );
    }

    /**
     * @return array
     */
    public function getArticles(): array
    {
        return $this->articles;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }
}
