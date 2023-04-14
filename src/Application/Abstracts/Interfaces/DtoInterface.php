<?php

namespace App\Application\Abstracts\Interfaces;

interface DtoInterface
{
    public function getUuid(): ?string;

    public function getCreatedAt(): ?\DateTime;
}
