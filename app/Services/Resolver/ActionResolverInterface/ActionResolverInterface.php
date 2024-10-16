<?php

namespace App\Services\Resolver\ActionResolverInterface;

use App\Command\Resolve\DTO\BadResponse;

interface ActionResolverInterface
{
    public function resolve(BadResponse $response);
}
