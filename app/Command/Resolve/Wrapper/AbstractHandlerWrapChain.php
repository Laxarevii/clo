<?php

namespace App\Command\Resolve\Wrapper;

abstract class AbstractHandlerWrapChain implements HandlerWrapChainInterface
{
    protected ?HandlerWrapChainInterface $nextHandler = null;
    public function setNext(?HandlerWrapChainInterface $nextHandler): ?HandlerWrapChainInterface
    {
        $this->nextHandler = $nextHandler;
        return $nextHandler;
    }
}
