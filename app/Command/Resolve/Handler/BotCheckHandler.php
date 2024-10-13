<?php

namespace App\Command\Resolve\Handler;

use App\Command\Resolve\Command;
use App\Command\Resolve\DTO\BadResponse;
use App\Command\Resolve\DTO\Response;
use App\Command\Resolve\DTO\SuccessResponse;
use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Common\DTO\Ip;
use App\Services\Detector\BotDetector\BotDetectorInterface;

class BotCheckHandler extends AbstractCheckHandler
{
    public function __construct(
        private BotDetectorInterface $botDetector,
    )
    {
    }

    public function handle(Command $command): Response
    {
        if ($this->botDetector->isBotIp($command->getIp())) {
            return new BadResponse('Bot Ip');
        }
        return $this->nextHandler ? $this->nextHandler->handle($command) : new SuccessResponse();
    }
}
