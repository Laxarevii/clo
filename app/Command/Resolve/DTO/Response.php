<?php

namespace App\Command\Resolve\DTO;

use App\Command\Resolve\Interface\ResponseInterface;

abstract class Response implements ResponseInterface
{
    public function __construct(private bool $status, private ?string $message = null)
    {
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
