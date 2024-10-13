<?php

namespace App\Command\Resolve\Interface;

interface ResponseInterface
{
    public function getStatus(): bool;

    public function getMessage(): ?string;
}
