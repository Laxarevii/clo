<?php

namespace App\Command\Common\DTO;


class SuccessResponse extends Response
{
    public function __construct()
    {
        parent::__construct(true);
    }
}
