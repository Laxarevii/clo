<?php

namespace App\Command\Resolve\Handler;

use App\Command\Common\DTO\BadResponse;
use App\Command\Common\DTO\SuccessResponse;
use App\Command\Resolve\Command;
use App\Command\Resolve\Handler\HandlerAggregatorObject\HandlerAggregatorObject;

class HandlerAggregator extends AbstractCheckHandler
{
    /** @var array<HandlerAggregatorObject> $handlers */
    public function __construct(private array $objects)
    {
    }

    public function handle(Command $command)
    {
        foreach ($this->objects as $object) {
            $res = $object->getHandler()->handle($command);

            if (!$res instanceof BadResponse) {
                return $object->getResolver()->execute();
            }
        }

        return $this->nextHandler ? $this->nextHandler->handle($command) : new SuccessResponse();
    }
}
