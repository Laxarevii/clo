<?php

namespace App\Command\Resolve;

use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Command\Resolve\Interface\CommandHandlerInterface;
use App\Command\Resolve\Interface\ResponseInterface;

class CommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CheckHandlerInterface $handlerChain
    ) {
    }

    public function handle(Command $command): ResponseInterface
    {
        return $this->handlerChain->handle($command);
    }
}
