<?php

namespace App\Command\Resolve\Block\Interface;


use App\Command\Resolve\Block\Command;

interface CommandHandlerInterface
{
    public function handle(Command $command): ResponseInterface;
}
