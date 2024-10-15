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
    public function detectName(UserAgent $userAgent): string
    {
        return match (true) {
            $this->isChromeOs($userAgent) => Os::CHROME_OS,
            $this->isIOS($userAgent) => Os::IOS,
            $this->isOSX($userAgent) => Os::OS_X,
            //TODO add others
            default => throw new UnknownOSException(),
        };
    }

    private function isChromeOs(UserAgent $userAgent): bool
    {
        return stripos($userAgent->getValue(), ' CrOS') !== false
            || stripos($userAgent->getValue(), 'CrOS ') !== false;
    }

    private function isIOS(UserAgent $userAgent): bool
    {
        $userAgentValue = $userAgent->getValue();
        return (
                stripos($userAgentValue, 'CPU OS') !== false ||
                stripos($userAgentValue, 'iPhone OS') !== false
            ) && stripos($userAgentValue, 'OS X') !== false;
    }

    private function isOSX(UserAgent $userAgent): bool
    {
        return stripos($userAgent->getValue(), 'OS X') !== false;
    }
}
