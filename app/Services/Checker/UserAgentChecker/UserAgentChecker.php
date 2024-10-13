<?php

namespace App\Services\Checker\UserAgentChecker;

use App\Common\DTO\UserAgent;

class UserAgentChecker implements UserAgentCheckerInterface
{
    public function __construct(private array $blockedUserAgents)
    {
    }

    public function isBlocked(UserAgent $userAgent): bool
    {
        $userAgentString = $userAgent->getValue();

        foreach ($this->blockedUserAgents as $blockedUserAgent) {
            if (!empty(stristr($userAgentString, $blockedUserAgent))) {
                return true;
            }
        }
        return false;
    }
}
