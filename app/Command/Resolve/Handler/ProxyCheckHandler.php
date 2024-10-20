<?php

namespace App\Command\Resolve\Handler;

use App\Command\Common\DTO\BadResponse;
use App\Command\Common\DTO\Response;
use App\Command\Common\DTO\SuccessResponse;
use App\Command\Resolve\Command;
use App\Services\Detector\ProxyDetector\ProxyDetectorInterface;

class ProxyCheckHandler extends AbstractCheckHandler
{
    public function __construct(
        private ProxyDetectorInterface $proxyDetector,
    ) {
    }

    public function handle(Command $command): Response
    {
        if ($this->proxyDetector->detect($command->getIp())) {
            return new BadResponse('Using Proxy');
        }
        return $this->nextHandler ? $this->nextHandler->handle($command) : new SuccessResponse();
    }
}
