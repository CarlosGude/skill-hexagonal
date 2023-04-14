<?php

namespace App\Domain\Entity;

class Author extends AbstractEntity
{
    protected ?string $email;

    protected ?string $name;

    /**
     * @var array<int, Article>
     */
    protected array $articles = array();

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): Author
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Author
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param array $articles
     * @return Author
     */
    public function setArticles(array $articles): Author
    {
        $this->articles = $articles;
        return $this;
    }


    /**
     * @return array<int, Article>
     */
    public function getArticles(): array
    {
        return $this->articles;
    }

    public function addArticle(Article $article): void
    {
        $this->articles[] = $article;
    }


}
