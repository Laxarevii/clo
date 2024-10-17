<?php

namespace App\Services\Resolver\ActionResolverInterface\BlockActionResolver;

use App\Action\ActionInterface;
use App\Action\Error404Strategy;
use App\Action\LoadCurlStrategy;
use App\Action\LoadLocalPageStrategy;
use App\Action\RedirectStrategy;
use Psr\Container\ContainerInterface;

class BlockActionResolverFactory
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function create(string $action): ActionInterface
    {
        return match ($action) {
            'curl' => $this->container->get('BlockLoadCurlStrategy'),
            'localPage' => $this->container->get('BlockLoadLocalPageStrategy'),
            'error_404' => $this->container->get(Error404Strategy::class),
            'redirect' => $this->container->get('BlockRedirectStrategy'),
            default => new \InvalidArgumentException()
        };
    }
}
