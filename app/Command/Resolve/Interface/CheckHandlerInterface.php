<?php

namespace App\Command\Resolve\Interface;

use App\Command\Resolve\Command;

interface CheckHandlerInterface
{
    public function handle(Command $command);
}
