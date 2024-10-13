<?php

namespace App\Command\Resolve\Interface;

use App\Command\Resolve\Command;
use App\Command\Resolve\DTO\Response;

interface CheckHandlerInterface
{
    public function setNext(?CheckHandlerInterface $handler): ?CheckHandlerInterface;
    public function handle(Command $command): Response;
}
