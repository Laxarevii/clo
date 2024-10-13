<?php

namespace App\Command\Resolve\Handler;

use App\Command\Resolve\Command;
use App\Command\Resolve\DTO\BadResponse;
use App\Command\Resolve\DTO\Response;
use App\Command\Resolve\DTO\SuccessResponse;
use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Common\DTO\Ip;
use App\Services\Detector\CountryDetector\CountryDetectorInterface;
use App\Services\Detector\IspDetector\IspDetectorInterface;
use App\Services\Detector\OsDetector\OsDetectorInterface;

class IspCheckHandler extends AbstractCheckHandler
{
    public function __construct(
        private array $blockedIsps,
        private IspDetectorInterface $ispDetector,
    )
    {
    }

    public function handle(Command $command): Response
    {
        $userIsp = $this->ispDetector->detect($command->getIp());
        if ($this->isBlockedIsp($userIsp)) {
            return new BadResponse('Blocked ISP');
        }
        return $this->nextHandler ? $this->nextHandler->handle($command) : new SuccessResponse();
    }

    private function isBlockedIsp(\App\Common\DTO\Isp $userIsp): bool
    {
        return in_array($userIsp->getValue(), $this->blockedIsps, true);
    }
}
