<?php

namespace App\Services\Resolver\ActionResolverInterface\AllowActionResolver;

use App\Action\ActionInterface;
use App\Action\Error404Strategy;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;

class AllowActionResolverFactory
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function create(string $action): ActionInterface
    {
        return match ($action) {
            'localPage' => $this->container->get('AllowLoadLocalPageStrategy'),
            'error_404' => $this->container->get(Error404Strategy::class),
            'redirect' => $this->container->get('AllowRedirectStrategy'),
            'curl' => $this->container->get('AllowCurlStrategy'),
            default => new InvalidArgumentException()
        };
    }
}
