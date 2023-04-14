<?php

namespace App\Application\Authors\DataTransformer;

use App\Application\Abstracts\DataTransformer\AbstractDataTransformer;
use App\Application\Abstracts\Interfaces\DtoInterface;
use App\Application\Authors\Dto\AuthorDto;
use App\Domain\Entity\AbstractEntity;
use App\Domain\Entity\Author;
use ContainerVrZBffQ\get_Console_Command_ConfigDumpReference_LazyService;

class AuthorDataTransformer extends AbstractDataTransformer
{
    /**
     * @param Author $data
     */
    protected function getDto(AbstractEntity $data): AuthorDto
    {
        return new AuthorDto(
            uuid: $data->getUuid(),
            name: $data->getName(),
            email: $data->getEmail(),
            createdAt: $data->getCreatedAt()
        );
    }
}
