<?php

namespace App\Command\Resolve;

use App\Action\ActionInterface;
use App\Command\Resolve\Interface\CommandHandlerInterface;
use App\Command\Resolve\Wrapper\HandlerWrapChainInterface;

class CommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private HandlerWrapChainInterface $handlerChain,
    ) {
    }

    public function handle(Command $command): ActionInterface
    {
        return $this->handlerChain->handle($command);
    }
}
