<?php

namespace App\Application\Abstracts;

use App\Application\Abstracts\Interfaces\Input\DtoInterface;

abstract class AbstractValidator
{
    protected const VALUE_NULL = 'This value can not be null or empty.';
    protected const VALUE_NOT_STRING = 'This value can should be a string.';

    /**
     * @return array<string, string>
     */
    abstract public function validate(DtoInterface $dto): array;
}
