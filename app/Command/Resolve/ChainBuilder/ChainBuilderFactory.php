<?php

namespace App\Command\Resolve\ChainBuilder;

class ChainBuilderFactory
{

    public function __construct(private array $conf)
    {
    }

    public function create()
    {
        return new ChainBuilder($this->conf);
    }
}
