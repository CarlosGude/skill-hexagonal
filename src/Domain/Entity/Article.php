<?php

namespace App\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="Article")
 *
 * @ORM\Entity
 */
class Article extends AbstractEntity
{
    /**
     * @ORM\Column(name="title", type="string", length=150, nullable=false)
     */
    #[Assert\NotBlank]
    protected ?string $title;

    /**
     * @ORM\Column(name="body", type="text", nullable=false)
     */
    #[Assert\NotBlank]
    protected ?string $body;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Entity\Author", inversedBy="articles")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    #[Assert\NotNull]
    protected ?Author $author;

    public function __construct(Author $author = null)
    {
        parent::__construct();
        $this->author = $author;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): Article
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): Article
    {
        $this->body = $body;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }
}
