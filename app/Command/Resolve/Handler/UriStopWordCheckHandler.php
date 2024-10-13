<?php

namespace App\Command\Resolve\Handler;

use App\Command\Resolve\Command;
use App\Command\Resolve\DTO\BadResponse;
use App\Command\Resolve\DTO\Response;
use App\Command\Resolve\DTO\SuccessResponse;

class UriStopWordCheckHandler extends AbstractCheckHandler
{
    public function __construct(private array $stopWords)
    {
    }

    public function handle(Command $command): Response
    {
        $uri = $command->getUri();
        if ($this->hasStopWord((string)$uri)) {
            return new BadResponse('No Referer');
        }

        return $this->nextHandler ? $this->nextHandler->handle($command) : new SuccessResponse();
    }

    private function hasStopWord(string $uri): bool
    {
        foreach ($this->stopWords as $stopWord) {
            if ($stopWord === '') {
                continue;
            };
            if (stripos($uri, $stopWord)) {
                return true;
            }
        }
        return false;
    }
}