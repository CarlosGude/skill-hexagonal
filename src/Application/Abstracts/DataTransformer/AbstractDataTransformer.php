<?php

namespace App\Application\Abstracts\DataTransformer;

use App\Application\Abstracts\Interfaces\DtoInterface;
use App\Domain\Entity\AbstractEntity;

abstract class AbstractDataTransformer
{
    /**
     * @param array<int, AbstractEntity>|AbstractEntity|null $data
     *
     * @return array<int, DtoInterface>|DtoInterface|null
     */
    public function transform(null|array|AbstractEntity $data): null|array|DtoInterface
    {
        if (!$data) {
            return null;
        }

        if (!is_array($data)) {
            return $this->getDto($data);
        }

        $bachResponse = [];
        foreach ($data as $item) {
            $bachResponse[] = $this->getDto($item);
        }

        return $bachResponse;
    }

    abstract protected function getDto(AbstractEntity $data): DtoInterface;
}
