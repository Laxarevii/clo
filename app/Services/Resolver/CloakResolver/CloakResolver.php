<?php

namespace App\Services\Resolver\CloakResolver;

use App\Command\Resolve\DTO\BadResponse;
use App\Command\Resolve\Interface\ResponseInterface;
use App\Services\Resolver\ActionResolverInterface\ActionResolverInterface;

class CloakResolver implements CloakResolverInterface
{
    public function __construct(private ActionResolverInterface $actionResolver)
    {
    }

    public function resolve(ResponseInterface $response)
    {
        if ($response instanceof BadResponse) {
            return $this->actionResolver->resolve($response);
        }
        return $response;
    }
}
