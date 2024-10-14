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

class StopWordsRefererCheckHandler extends AbstractCheckHandler
{
    public function __construct(private array $stopWords)
    {
    }

    public function handle(Command $command): Response
    {
        $userReferer = $command->getReferer();
        if ($this->hasStopWord($userReferer->getValue())) {
            return new BadResponse('No Referer');
        }

        return $this->nextHandler ? $this->nextHandler->handle($command) : new SuccessResponse();
    }

    private function hasStopWord(?string $userReferer): bool
    {
        foreach ($this->stopWords as $stopWord) {
            if ($stopWord === '') {
                continue;
            };
            if (stripos($userReferer, $stopWord)) {
                return true;
            }
        }
        return false;
    }
}
