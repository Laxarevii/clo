<?php

namespace App\Command\Resolve\Handler;

use App\Command\Common\DTO\BadResponse;
use App\Command\Common\DTO\Response;
use App\Command\Common\DTO\SuccessResponse;
use App\Command\Resolve\Command;

class UriShouldContainCheckHandler extends AbstractCheckHandler
{
    public function __construct(private array $words)
    {
    }

    public function handle(Command $command): Response
    {
        $uri = $command->getUri();

        if (!$this->containsWords((string)$uri)) {
            return new BadResponse('URI does not contain words');
        }

        return $this->nextHandler ? $this->nextHandler->handle($command) : new SuccessResponse();
    }

    private function containsWords(string $uri): bool
    {
        foreach ($this->words as $word) {
            if (!str_contains($uri, $word)) {
                return false;
            }
        }
        return true;
    }
}
