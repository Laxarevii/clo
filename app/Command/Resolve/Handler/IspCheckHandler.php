<?php

namespace App\Command\Resolve\Handler;

use App\Command\Common\DTO\BadResponse;
use App\Command\Common\DTO\Response;
use App\Command\Common\DTO\SuccessResponse;
use App\Command\Resolve\Command;
use App\Entity\Isp;
use App\Services\Detector\IspDetector\IspDetectorInterface;

class IspCheckHandler extends AbstractCheckHandler
{
    public function __construct(
        private array $blockedIsps,
        private IspDetectorInterface $ispDetector,
    ) {
    }

    public function handle(Command $command): Response
    {
        $userIsp = $this->ispDetector->detect($command->getIp());
        if ($this->isBlockedIsp($userIsp)) {
            return new BadResponse('Blocked ISP');
        }
        return $this->nextHandler ? $this->nextHandler->handle($command) : new SuccessResponse();
    }

    private function isBlockedIsp(Isp $userIsp): bool
    {
        return in_array($userIsp->getValue(), $this->blockedIsps, true);
    }
}
