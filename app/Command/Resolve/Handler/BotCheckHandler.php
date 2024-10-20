<?php

namespace App\Command\Resolve\Handler;

use App\Command\Common\DTO\BadResponse;
use App\Command\Common\DTO\Response;
use App\Command\Common\DTO\SuccessResponse;
use App\Command\Resolve\Command;
use App\Services\Detector\BotDetector\BotDetectorInterface;

class BotCheckHandler extends AbstractCheckHandler
{
    public function __construct(
        private BotDetectorInterface $botDetector,
    ) {
    }

    public function handle(Command $command)
    {
        if ($this->botDetector->isBotIp($command->getIp())) {
            return new BadResponse('Bot Ip');
        }
        return $this->nextHandler ? $this->nextHandler->handle($command) : new SuccessResponse();
    }
}
