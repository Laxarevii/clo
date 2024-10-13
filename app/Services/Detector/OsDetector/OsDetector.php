<?php

namespace App\Services\Detector\OsDetector;

use App\Common\DTO\Os;
use App\Common\DTO\UserAgent;
use App\Exceptions\UnknownOSException;

class OsDetector implements OsDetectorInterface
{
    /**
     * @throws \App\Exceptions\UnknownOSException
     */
    public function detect(UserAgent $userAgent): Os
    {
        return match (true) {
            // Chrome OS before OS X
            $this->isChromeOs($userAgent) => Os::createChrome($userAgent),
            // iOS before OS X
            $this->isIOS($userAgent) => Os::createIOS($userAgent),
            $this->isOSX($userAgent) => Os::createOSX($userAgent),
            //TODO add others
            default => throw new UnknownOSException(),
        };
    }

    private function isChromeOs(UserAgent $userAgent): bool
    {
        return stripos($userAgent->getValue(), ' CrOS')
            || stripos($userAgent->getValue(), 'CrOS ');
    }

    private function isIOS(UserAgent $userAgent): bool
    {
        $userAgentValue = $userAgent->getValue();
        return stripos($userAgentValue, 'CPU OS') ||
            stripos($userAgentValue, 'iPhone OS') &&
            stripos($userAgentValue, 'OS X');
    }

    private function isOSX(UserAgent $userAgent): bool
    {
        return stripos($userAgent->getValue(), 'OS X');
    }
}