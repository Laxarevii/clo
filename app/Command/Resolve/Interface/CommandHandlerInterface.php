<?php

namespace App\Command\Resolve\Interface;

use App\Command\Resolve\Command;

interface CommandHandlerInterface
{
    public function handle(Command $command);
}
