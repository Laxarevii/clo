<?php

namespace App\Command\Resolve\Handler;

use App\Command\Resolve\Interface\CheckHandlerInterface;

abstract class AbstractCheckHandler implements CheckHandlerInterface
{
    protected ?CheckHandlerInterface $nextHandler = null;

    public function setNext(?CheckHandlerInterface $handler): ?CheckHandlerInterface
    {
        $this->nextHandler = $handler;
        return $handler;
    }
}
