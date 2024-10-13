<?php

namespace App\Command\Resolve\Handler;

use App\Command\Resolve\Command;
use App\Command\Resolve\DTO\BadResponse;
use App\Command\Resolve\DTO\Response;
use App\Command\Resolve\DTO\SuccessResponse;
use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Common\DTO\Ip;
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
