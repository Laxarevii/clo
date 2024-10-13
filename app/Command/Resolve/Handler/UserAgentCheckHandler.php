<?php

namespace App\Command\Resolve\Handler;

use App\Command\Resolve\Command;
use App\Command\Resolve\DTO\BadResponse;
use App\Command\Resolve\DTO\Response;
use App\Command\Resolve\DTO\SuccessResponse;
use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Common\DTO\Ip;
use App\Services\Checker\UserAgentChecker\UserAgentCheckerInterface;

class UserAgentCheckHandler extends AbstractCheckHandler
{
    public function __construct(private UserAgentCheckerInterface $userAgentChecker)
    {
    }

    public function handle(Command $command): Response
    {
        $userUserAgent = $command->getUserAgent();

        if ($this->userAgentChecker->isBlocked($userUserAgent)) {
            return new BadResponse('UserAgent blocked');
        }
        return $this->nextHandler ? $this->nextHandler->handle($command) : new SuccessResponse();
    }
}
