<?php

namespace App\Services\Checker\UserAgentChecker;

use App\Entity\UserAgent;

class UserAgentChecker implements UserAgentCheckerInterface
{
    /**
     * @param string[] $blockedUserAgents
     */
    public function __construct(private array $blockedUserAgents)
    {
    }

    public function isBlocked(UserAgent $userAgent): bool
    {
        $userAgentString = $userAgent->getValue();

        foreach ($this->blockedUserAgents as $blockedUserAgent) {
            if (stristr($userAgentString, $blockedUserAgent) !== false) {
                return true;
            }
        }
        return false;
    }
}
