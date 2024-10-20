<?php

namespace App\Command\Resolve;

use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Command\Resolve\Interface\CommandHandlerInterface;

class CommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CheckHandlerInterface $handlerChain
    ) {
    }

    public function handle(Command $command)
    {
        return $this->handlerChain->handle($command);
    }
}
