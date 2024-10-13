<?php

namespace App\Command\Resolve\Factory;

use App\Command\Resolve\CommandHandler;
use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Command\Resolve\Interface\CommandHandlerInterface;

class CommandHandlerFactory
{
    public function __construct(private CheckHandlerInterface $checkHandler) {}

    public function create(): CommandHandlerInterface
    {
        return new CommandHandler($this->checkHandler);
    }
}
