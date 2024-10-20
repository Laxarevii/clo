<?php

namespace App\Command\Resolve\Block\Interface;

interface ResponseInterface
{
    public function getStatus(): bool;

    public function getMessage(): ?string;
}
