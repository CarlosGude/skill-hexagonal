<?php

namespace App\Infrastructure\Dto;

use App\Application\Abstracts\Interfaces\DtoInterface;
use Symfony\Component\HttpFoundation\Response;

class ResponseDto
{
    /**
     * @param array<int,DtoInterface>|DtoInterface|null $content
     */
    public function __construct(
        protected readonly null|array|DtoInterface $content = [],
        protected readonly int $code = Response::HTTP_ACCEPTED
    ) {
    }

    /**
     * @return array<int,DtoInterface>|DtoInterface|null
     */
    public function getContent(): null|array|DtoInterface
    {
        return $this->content;
    }

    public function getCode(): int
    {
        return $this->code;
    }
}
