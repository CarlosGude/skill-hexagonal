<?php

namespace App\Application\Abstracts\DataTransformer\Input;

use App\Application\Abstracts\Interfaces\Input\DtoInterface;

abstract class AbstractDataTransformer
{
    /**
     * @param array<string, string> $request
     *
     * @return array<string, string>|DtoInterface
     */
    abstract public function requestToDto(array $request): array|DtoInterface;
}
