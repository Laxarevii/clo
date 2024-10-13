<?php

namespace App\Command\Resolve\Interface;

use App\Command\Resolve\Command;
use App\Command\Resolve\DTO\Response;

interface CommandHandlerInterface
{
    public function handle(Command $command): ResponseInterface;
}
