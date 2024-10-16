<?php

namespace App\Services\Resolver\ActionResolverInterface;

use App\Command\Resolve\DTO\BadResponse;
use App\Command\Resolve\DTO\Response;
use App\Command\Resolve\Interface\ResponseInterface;

interface ActionResolverInterface
{
    public function resolve();
}
