<?php

namespace App\Command\Resolve\DTO;

class BadResponse extends Response
{
    public function __construct(string $message)
    {
        parent::__construct(false, $message);
    }
}
