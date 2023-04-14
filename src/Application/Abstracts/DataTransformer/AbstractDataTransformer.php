<?php

namespace App\Application\Abstracts\DataTransformer;

use App\Application\Abstracts\Interfaces\DtoInterface;
use App\Domain\Entity\AbstractEntity;

abstract class AbstractDataTransformer
{
    public function transformFromEntity(AbstractEntity $data): DtoInterface
    {
        return $this->getDto($data);
    }

    /**
     * @param array<int,AbstractEntity> $data
     *
     * @return array<int,DtoInterface>
     */
    public function transformArray(array $data): array
    {
        $bachResponse = [];
        foreach ($data as $item) {
            $bachResponse[] = $this->getDto($item);
        }

        return $bachResponse;
    }

    abstract protected function getDto(AbstractEntity $data): DtoInterface;
}
