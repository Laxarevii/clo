<?php

namespace App\Services\Checker\UserAgentChecker;

use App\Common\DTO\UserAgent;

interface UserAgentCheckerInterface
{
    public function isBlocked(UserAgent $userAgent): bool;
}
