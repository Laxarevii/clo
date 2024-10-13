<?php

namespace App\Command\Resolve\DTO;

use App\Command\Resolve\Interface\ResponseInterface;

class SuccessResponse extends Response
{
    public function __construct()
    {
        parent::__construct(true);
    }
}
