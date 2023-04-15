<?php

namespace App\Application\Abstracts\Interfaces\Output;

interface DtoInterface
{
    public function getUuid(): string;

    public function getCreatedAt(): \DateTime;
}
