<?php

namespace App\Services\Resolver\ActionResolverInterface\BlockActionResolver;

use App\Services\Action\Block\BlockActionStrategyInterface;
use App\Services\Action\Block\Error404Strategy;
use App\Services\Action\Block\LoadCurlStrategy;
use App\Services\Action\Block\LoadLocalPageStrategy;
use App\Services\Action\Block\RedirectStrategy;
use Psr\Container\ContainerInterface;

class BlockActionResolverFactory
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function create(string $action): BlockActionStrategyInterface
    {
        return match ($action) {
            'curl' => $this->container->get(LoadCurlStrategy::class),
            'localPage' => $this->container->get(LoadLocalPageStrategy::class),
            'error_404' => $this->container->get(Error404Strategy::class),
            'redirect' => $this->container->get(RedirectStrategy::class),
            default => new \InvalidArgumentException()
        };
    }
}
