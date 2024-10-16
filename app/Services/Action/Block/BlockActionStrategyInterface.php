<?php

namespace App\Services\Action\Block;

use App\Command\Resolve\DTO\BadResponse;

interface BlockActionStrategyInterface
{
    public function execute(BadResponse $response);
}
