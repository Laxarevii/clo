<?php

namespace App\Command\Resolve\Block\Factory;

use App\Command\Resolve\Block\CommandHandler;
use App\Command\Resolve\Block\Interface\CheckBlockHandlerInterface;
use App\Command\Resolve\Block\Interface\CommandHandlerInterface;

class CommandHandlerFactory
{
    public function __construct(private CheckBlockHandlerInterface $checkHandler)
    {
    }

    public function create(): CommandHandlerInterface
    {
        return new CommandHandler($this->checkHandler);
    }
}
