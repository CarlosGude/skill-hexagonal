<?php

namespace App\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="Author")
 *
 * @ORM\Entity
 */
class Author extends AbstractEntity
{
    /**
     * @ORM\Column(name="emil", type="text", nullable=false)
     */
    #[Assert\NotBlank]
    #[Assert\Email]
    protected ?string $email;

    /**
     * @ORM\Column(name="name", type="text", nullable=false)
     */
    #[Assert\NotBlank]
    protected ?string $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Entity\Article",orphanRemoval=true, mappedBy="author")
     */
    protected Collection $articles;

    public function __construct()
    {
        parent::__construct();
        $this->articles = new ArrayCollection();
    }

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
     * @param array<int,Article> $articles
     *
     * @return $this
     */
    public function setArticles(array $articles): Author
    {
        foreach ($articles as $article) {
            $this->articles->add($article);
        }

        return $this;
    }

    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): void
    {
        $this->articles[] = $article;
    }
}
