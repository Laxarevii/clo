<?php

namespace App\Command\Resolve\Block\Interface;

use App\Command\Resolve\Block\Command;

interface CheckBlockHandlerInterface
{
    public function handle(Command $command);
}
