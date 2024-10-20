<?php

namespace App\Command\Resolve\Handler;

use App\Command\Common\DTO\BadResponse;
use App\Command\Common\DTO\Response;
use App\Command\Common\DTO\SuccessResponse;
use App\Command\Resolve\Command;

class WithOutRefererCheckHandler extends AbstractCheckHandler
{
    public function __construct(private bool $blockWithoutRefererFlag)
    {
    }

    public function handle(Command $command): Response
    {
        $userReferer = $command->getReferer();
        if ($this->blockWithoutRefererFlag && empty($userReferer->getValue())) {
            return new BadResponse('No Referer');
        }

        return $this->nextHandler ? $this->nextHandler->handle($command) : new SuccessResponse();
    }
}
