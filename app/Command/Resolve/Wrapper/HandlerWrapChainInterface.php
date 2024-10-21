<?php

namespace App\Command\Resolve\Wrapper;

use App\Action\ActionInterface;
use App\Command\Resolve\Command;
use App\Command\Resolve\Interface\CheckHandlerInterface;

interface HandlerWrapChainInterface
{
    public function handle(Command $command): ActionInterface;
}
