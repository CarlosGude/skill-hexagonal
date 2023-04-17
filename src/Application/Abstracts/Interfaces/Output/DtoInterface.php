<?php

namespace App\Application\Abstracts\Interfaces\Output;

use DateTime;

interface DtoInterface
{
    public function getUuid(): string;

    public function getCreatedAt(): DateTime;
}
