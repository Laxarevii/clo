<?php

namespace App\Services\Resolver\CloakResolver;

use App\Command\Resolve\Interface\ResponseInterface;

interface CloakResolverInterface
{
    public function resolve(ResponseInterface $response);
}
