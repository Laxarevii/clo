<?php

namespace App\Command\Resolve\Handler;

use App\Command\Common\DTO\BadResponse;
use App\Command\Common\DTO\SuccessResponse;
use App\Command\Resolve\Command;
use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Command\Resolve\Interface\ResponseInterface;

class HandlerAggregator extends AbstractCheckHandler
{
    /** @var array<CheckHandlerInterface> $handlers */
    public function __construct(private array $handlers)
    {
    }

    public function handle(Command $command): ResponseInterface
    {
        foreach ($this->handlers as $handler) {
            $res = $handler->handle($command);

            if (!$res instanceof BadResponse) {
                return $object->getResolver()->execute();
            }
        }

        return $this->nextHandler ? $this->nextHandler->handle($command) : new SuccessResponse();
    }
}
