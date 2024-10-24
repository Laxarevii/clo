<?php

namespace App\Command\Resolve\Interface;

use App\Action\ActionInterface;
use App\Command\Resolve\Command;

interface CommandHandlerInterface
{
    public function handle(Command $command): ActionInterface;
}
