<?php

namespace App\Domain\Entity;

class Author extends AbstractEntity
{
    protected ?string $email;

    protected ?string $name;

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
}
