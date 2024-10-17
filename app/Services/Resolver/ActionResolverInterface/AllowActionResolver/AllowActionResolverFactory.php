<?php

namespace App\Services\Resolver\ActionResolverInterface\AllowActionResolver;

use App\Action\ActionInterface;
use App\Action\Error404Strategy;
use App\Action\LoadCurlStrategy;
use App\Action\LoadLocalPageStrategy;
use App\Action\RedirectStrategy;
use Psr\Container\ContainerInterface;

class AllowActionResolverFactory
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function create(string $action): ActionInterface
    {
        return match ($action) {
            'redirect' => $this->container->get('AllowRedirectStrategy'),
            'curl' => $this->container->get('AllowCurlStrategy'),
            default => new \InvalidArgumentException()
        };
    }
}
