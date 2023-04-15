<?php

namespace App\Application\Abstracts;

use App\Application\Abstracts\Interfaces\Input\DtoInterface;

abstract class AbstractValidator
{
    public const VALUE_NULL = 'This value can not be null or empty.';
    public const INDEX_NULL = 'null_or_empty';
    public const VALUE_NOT_STRING = 'This value can should be a string.';

    public const INDEX_NOT_STRING = 'null_or_string';

    /**
     * @return array<string, array<string, string>>
     */
    abstract public function validate(DtoInterface $dto): array;
}
