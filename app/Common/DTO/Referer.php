<?php

namespace App\Common\DTO;

class Referer
{
    public function __construct(private ?string $value = null)
    {
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
