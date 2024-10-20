<?php

namespace App\Command\Resolve\Block\Handler;


use App\Command\Resolve\Block\Interface\CheckBlockHandlerInterface;

abstract class AbstractCheckHandler implements CheckBlockHandlerInterface
{
    protected ?CheckBlockHandlerInterface $nextHandler = null;

    public function setNext(?CheckBlockHandlerInterface $handler): ?CheckBlockHandlerInterface
    {
        $this->nextHandler = $handler;
        return $handler;
    }
}
