<?php

namespace App\Application\Authors\Dto;

use App\Application\Abstracts\Interfaces\DtoInterface;

class AuthorDto implements DtoInterface
{
    public function __construct(
        protected readonly ?string $uuid,
        protected readonly ?string $name,
        protected readonly ?string $email,
        protected readonly ?\DateTime $createdAt,
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
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
