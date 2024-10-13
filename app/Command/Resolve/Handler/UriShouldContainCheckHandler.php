<?php

namespace App\Command\Resolve\Handler;

use App\Command\Resolve\Command;
use App\Command\Resolve\DTO\BadResponse;
use App\Command\Resolve\DTO\Response;
use App\Command\Resolve\DTO\SuccessResponse;
use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Common\DTO\Ip;
use App\Services\Detector\CountryDetector\CountryDetectorInterface;
use App\Services\Detector\OsDetector\OsDetectorInterface;

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
