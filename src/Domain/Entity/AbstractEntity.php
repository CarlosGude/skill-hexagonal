<?php

namespace App\Domain\Entity;

use App\Domain\GenerateUuid;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     *
     * @ORM\Id
     *
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected ?int $id;

    /**
     * @ORM\Column(name="uuid", type="string", length=36, nullable=false)
     */
    protected string $uuid;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected \DateTime $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    protected \DateTime $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->uuid = GenerateUuid::generate();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(): AbstractEntity
    {
        $this->updatedAt = new \DateTime();

        return $this;
    }
}
