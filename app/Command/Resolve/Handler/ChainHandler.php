<?php

namespace App\Command\Resolve\Handler;

use App\Command\Resolve\Command;
use App\Command\Resolve\DTO\BadResponse;
use App\Command\Resolve\DTO\Response;
use App\Command\Resolve\DTO\SuccessResponse;
use App\Command\Resolve\Interface\CheckHandlerInterface;

class ChainHandler extends AbstractCheckHandler
{
    /** @var array<CheckHandlerInterface> $handlers */
    public function __construct(private array $handlers)
    {
    }

    public function handle(Command $command): Response
    {
        foreach ($this->handlers as $handler) {
            if (($res = $handler->handle($command)) instanceof BadResponse) {
                return $res;
            }
        }
        return $this->nextHandler ? $this->nextHandler->handle($command) : new SuccessResponse();
    }
}
