<?php

namespace App\Command\Resolve\Wrapper;

use App\Action\ActionInterface;
use App\Command\Common\DTO\BadResponse;
use App\Command\Resolve\Command;

class HandlerAggregatorWrapChain extends AbstractHandlerWrapChain
{
    /**
     * @param array<HandlerActionWrap> $wrappers
     */
    public function __construct(
        private array $wrappers,
        private ActionInterface $action,
    ) {
    }

    /**
     * @param \App\Command\Resolve\Command $command
     * @return \App\Action\ActionInterface
     */
    public function handle(Command $command): ActionInterface
    {
        foreach ($this->wrappers as $wrapper) {
            $res = $wrapper->handle($command);
            if ($res instanceof BadResponse) {
                continue;
            }
            return $wrapper->getAction();
        }
        return $this->action;
    }
}
