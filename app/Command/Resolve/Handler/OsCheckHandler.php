<?php

namespace App\Command\Resolve\Handler;

use App\Command\Resolve\Command;
use App\Command\Resolve\DTO\BadResponse;
use App\Command\Resolve\DTO\Response;
use App\Command\Resolve\DTO\SuccessResponse;
use App\Common\DTO\Os;
use App\Services\Detector\OsDetector\OsDetectorInterface;

class OsCheckHandler extends AbstractCheckHandler
{
    public function __construct(
        private array $allowedOses,
        private OsDetectorInterface $osDetector,
    ) {
    }

    public function handle(Command $command): Response
    {
        $userOs = $this->osDetector->detect($command->getUserAgent());

        if ($this->isForbiddenOs(new Os($userOs->getValue(), $userOs->getVersion()))) {
            return new BadResponse('Forbidden OS');
        }

        return $this->nextHandler ? $this->nextHandler->handle($command) : new SuccessResponse();
    }

    private function isForbiddenOs(Os $userOs): bool
    {
        return !in_array($userOs->getValue(), $this->allowedOses);
    }
}
