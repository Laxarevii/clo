<?php

namespace App\Command\Resolve\Block;

use App\Command\Resolve\Block\Interface\CheckBlockHandlerInterface;
use App\Command\Resolve\Block\Interface\CommandHandlerInterface;
use App\Command\Resolve\Block\Interface\ResponseInterface;

class CommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CheckBlockHandlerInterface $handlerChain
    ) {
    }

    public function handle(Command $command): ResponseInterface
    {
        return $this->handlerChain->handle($command);
    }
}
