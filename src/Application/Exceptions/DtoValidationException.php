<?php

namespace App\Application\Exceptions;

class DtoValidationException extends \Exception
{
    /** @var array<string,array<string,string>> */
    protected array $errors;

    /**
     * @return array<string,array<string,string>>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array<string,array<string,string>> $errors
     *
     * @return $this
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }
}
