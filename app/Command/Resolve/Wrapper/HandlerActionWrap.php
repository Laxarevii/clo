<?php

namespace App\Command\Resolve\Wrapper;

use App\Action\ActionInterface;
use App\Command\Resolve\Command;
use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Command\Resolve\Interface\ResponseInterface;

class HandlerActionWrap implements CheckHandlerInterface
{
    public function __construct(
        private CheckHandlerInterface $handler,
        private ActionInterface $action,
    ) {
    }

    public function handle(Command $command): ResponseInterface
    {
        return $this->handler->handle($command);
    }

    /**
     * @return \App\Action\ActionInterface
     */
    public function getAction(): ActionInterface
    {
        return $this->action;
    }
}