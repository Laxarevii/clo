<?php

namespace App\Command\Resolve\Factory;

use App\Command\Resolve\Wrapper\HandlerWrapChainInterface;
use Exception;

class HandlerWrapChainFactory
{
    public function create(array $handlers): HandlerWrapChainInterface
    {
        if (empty($handlers)) {
            throw new Exception('No handlers configured.');
        }

        $firstHandler = array_shift($handlers);
        $currentHandler = $firstHandler;

        foreach ($handlers as $handler) {
            $currentHandler->setNext($handler);
            $currentHandler = $handler;
        }

        return $firstHandler;
    }
}
