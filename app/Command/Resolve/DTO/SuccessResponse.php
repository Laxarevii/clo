<?php

namespace App\Command\Resolve\DTO;


class SuccessResponse extends Response
{
    public function __construct()
    {
        parent::__construct(true);
    }
}
