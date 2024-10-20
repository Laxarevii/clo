<?php

namespace App\Services\Checker\UserAgentChecker;

use App\Entity\UserAgent;

interface UserAgentCheckerInterface
{
    public function isBlocked(UserAgent $userAgent): bool;
}
