<?php

namespace App\Domain\Entity;

use App\Domain\GenerateUuid;

abstract class AbstractEntity
{
    protected ?int $id;

    protected ?string $uuid;

    protected ?\DateTime $createdAt;

    protected ?\DateTime $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->uuid = GenerateUuid::generate();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(): AbstractEntity
    {
        $this->updatedAt = new \DateTime();

        return $this;
    }
}
