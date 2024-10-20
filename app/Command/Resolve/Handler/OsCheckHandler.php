<?php

namespace App\Command\Resolve\Handler;

use App\Command\Common\DTO\BadResponse;
use App\Command\Common\DTO\Response;
use App\Command\Common\DTO\SuccessResponse;
use App\Command\Resolve\Command;
use App\Entity\Os;
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

        if ($this->isForbiddenOs($userOs)) {
            return new BadResponse('Forbidden OS');
        }

        return $this->nextHandler ? $this->nextHandler->handle($command) : new SuccessResponse();
    }

    private function isForbiddenOs(Os $userOs): bool
    {
        return !in_array($userOs->getName(), $this->allowedOses);
    }
}
