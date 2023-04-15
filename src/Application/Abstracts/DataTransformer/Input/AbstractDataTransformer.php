<?php

namespace App\Application\Abstracts\DataTransformer\Input;

use App\Application\Abstracts\Interfaces\Input\DtoInterface;

abstract class AbstractDataTransformer
{
    /**
     * @param array<string, string> $request
     */
    abstract public function requestToDto(array $request): DtoInterface;
}
