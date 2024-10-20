<?php

namespace App\Command\Resolve\Handler\HandlerAggregatorObject;

use App\Action\ActionInterface;
use App\Command\Resolve\Interface\CheckHandlerInterface;

class HandlerAggregatorObject
{
    public function __construct(
        private CheckHandlerInterface $handler,
        private ActionInterface $resolver
    ) {
    }

    /**
     * @return \App\Command\Resolve\Interface\CheckHandlerInterface
     */
    public function getHandler(): CheckHandlerInterface
    {
        return $this->handler;
    }

    /**
     * @return \App\Action\ActionInterface
     */
    public function getResolver(): ActionInterface
    {
        return $this->resolver;
    }
}
