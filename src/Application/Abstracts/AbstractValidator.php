<?php

namespace App\Application\Abstracts;

use App\Application\Abstracts\Interfaces\Input\DtoInterface;

abstract class AbstractValidator
{
    public const VALUE_NULL = 'This value can not be null or empty.';

    /**
     * @return array<string, array<string, string>>
     */
    abstract public function validate(DtoInterface $dto): array;
}
