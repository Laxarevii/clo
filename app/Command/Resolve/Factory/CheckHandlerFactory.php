<?php

namespace App\Command\Resolve\Factory;

use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Config\Config;
use Exception;

class CheckHandlerFactory
{
    public function create(array $handlers): CheckHandlerInterface
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
