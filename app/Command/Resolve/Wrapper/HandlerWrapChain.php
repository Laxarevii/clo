<?php

namespace App\Command\Resolve\Wrapper;

use App\Action\ActionInterface;
use App\Command\Common\DTO\BadResponse;
use App\Command\Resolve\Command;
use App\Command\Resolve\Interface\CheckHandlerInterface;

class HandlerWrapChain extends AbstractHandlerWrapChain
{
    public function __construct(
        private CheckHandlerInterface $handler,
        private ActionInterface $action,
    ) {
    }

    public function handle(Command $command): ActionInterface
    {
        $res = $this->handler->handle($command);
        if ($res instanceof BadResponse) {
            return $this->action;
        }
       return $this->nextHandler ? $this->nextHandler->handle($command) : $this->action;
    }
}
