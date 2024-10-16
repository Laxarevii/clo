<?php

namespace App\Services\Resolver\ActionResolverInterface\AllowActionResolver;

use App\Action\ActionInterface;
use App\Services\Resolver\ActionResolverInterface\ActionResolverInterface;

class AllowActionResolver implements ActionResolverInterface
{
    public function __construct(private ActionInterface $strategy)
    {
    }

    public function resolve()
    {
        return $this->strategy->execute();
    }
}
