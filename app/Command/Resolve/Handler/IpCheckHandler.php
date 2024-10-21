<?php

namespace App\Command\Resolve\Handler;

use App\Command\Common\DTO\BadResponse;
use App\Command\Common\DTO\SuccessResponse;
use App\Command\Resolve\Command;
use App\Command\Resolve\Interface\ResponseInterface;
use App\Services\Detector\BlockedIpDetector\BlockedIpDetectorInterface;

class IpCheckHandler extends AbstractCheckHandler
{
    public function __construct(
        private BlockedIpDetectorInterface $blockedIpDetector,
    ) {
    }

    public function handle(Command $command): ResponseInterface
    {
        if ($this->blockedIpDetector->isBlockedIp($command->getIp())) {
            return new BadResponse('Blocked Ip');
        }
        return $this->nextHandler ? $this->nextHandler->handle($command) : new SuccessResponse();
    }
}
