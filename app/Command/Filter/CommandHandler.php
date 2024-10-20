<?php

namespace App\Command\Filter;

class CommandHandler
{
    public function __construct(
        private $handlerChain
    ) {
    }

    public function handle(Command $command)
    {
        return $this->handlerChain->handle($command);
    }
}
