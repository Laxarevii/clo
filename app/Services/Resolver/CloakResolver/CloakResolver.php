<?php

namespace App\Services\Resolver\CloakResolver;

use App\Command\Resolve\DTO\BadResponse;
use App\Command\Resolve\DTO\SuccessResponse;
use App\Command\Resolve\Interface\ResponseInterface;
use App\Services\Resolver\ActionResolverInterface\ActionResolverInterface;

class CloakResolver implements CloakResolverInterface
{
    public function __construct(
        private ActionResolverInterface $blockActionResolver,
        private ActionResolverInterface $allowActionResolver,
    ) {
    }

    public function resolve(ResponseInterface $response)
    {
        if ($response instanceof BadResponse) {
            return $this->blockActionResolver->resolve();
        }
        if ($response instanceof SuccessResponse) {
            return $this->allowActionResolver->resolve();
        }
        throw new \InvalidArgumentException($response::class . ' invalid response type');
    }
}
