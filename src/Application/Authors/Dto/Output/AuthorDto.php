<?php

namespace App\Application\Authors\Dto\Output;

use App\Application\Abstracts\Interfaces\Output\DtoInterface;
use App\Application\Articles\Dto\Output\ArticleDto;
use App\Domain\Entity\Article;

class AuthorDto implements DtoInterface
{
    /** @var array <int, ArticleDto> */
    protected array $articles = [];

    /**
     * @param array <int, Article> $articlesEntity
     */
    public function __construct(
        protected readonly string $uuid,
        protected readonly string $name,
        protected readonly string $email,
        protected readonly \DateTime $createdAt,
        array $articlesEntity = [],
    ) {
        foreach ($articlesEntity as $article) {
            $this->addArticle($article);
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    protected function addArticle(Article $article): void
    {
        $this->articles[] = new ArticleDto(
            uuid: $article->getUuid(),
            title: (string) $article->getTitle(),
            body: (string) $article->getBody(),
            createdAt: $article->getCreatedAt(),
        );
    }

    /**
     * @return array<int, ArticleDto>
     */
    public function getArticles(): array
    {
        return $this->articles;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
