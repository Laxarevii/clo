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

class WithOutRefererCheckHandler extends AbstractCheckHandler
{
    public function __construct(private bool $blockWithoutRefererFlag)
    {
    }

    public function handle(Command $command): Response
    {
        $userReferer = $command->getReferer();
        if ($this->blockWithoutRefererFlag && empty($userReferer->getValue())) {
            return new BadResponse('No Referer');
        }

        return $this->nextHandler ? $this->nextHandler->handle($command) : new SuccessResponse();
    }
}

