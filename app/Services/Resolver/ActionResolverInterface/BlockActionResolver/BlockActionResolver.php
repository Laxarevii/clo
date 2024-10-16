<?php

namespace App\Services\Resolver\ActionResolverInterface\BlockActionResolver;

use App\Command\Resolve\DTO\BadResponse;
use App\Services\Action\Block\BlockActionStrategyInterface;
use App\Services\Resolver\ActionResolverInterface\ActionResolverInterface;

class BlockActionResolver implements ActionResolverInterface
{
    public function __construct(private BlockActionStrategyInterface $strategy)
    {
    }

    public function resolve(BadResponse $response)
    {
        return $this->strategy->execute($response);
    }
}
