<?php

namespace App\Common\DTO;

abstract class AbstractSimpleDTO
{
    public function __construct(private string $value)
    {
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
