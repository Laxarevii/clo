<?php

namespace App\Entity;

class Country
{
    public function __construct(private string $isoCode)
    {
    }

    public function getIsoCode(): string
    {
        return $this->isoCode;
    }
}
