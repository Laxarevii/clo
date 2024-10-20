<?php

namespace App\Command\Resolve\Block\Factory;

use App\Command\Resolve\Block\Interface\CheckBlockHandlerInterface;
use Exception;

class CheckBlockHandlerFactory
{
    public function create(array $handlers): CheckBlockHandlerInterface
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
