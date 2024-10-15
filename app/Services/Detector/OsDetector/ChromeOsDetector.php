<?php

namespace App\Services\Detector\OsDetector;

use App\Common\DTO\Os;
use App\Common\DTO\UserAgent;

class ChromeOsDetector extends AbstractOsDetector
{
    protected function doDetect(UserAgent $userAgent): ?Os
    {
        if (
            stripos($userAgent->getValue(), ' CrOS') !== false ||
            stripos($userAgent->getValue(), 'CrOS ') !== false
        ) {
            return new Os('Chrome OS', $this->getVersionFromUserAgent($userAgent));
        }

        return null;
    }

    private function getVersionFromUserAgent(UserAgent $userAgent): string
    {
        if (preg_match('/Chrome\/([\d\.]*)/i', $userAgent->getValue(), $matches) !== false) {
            return $matches[1];
        }
        throw new \InvalidArgumentException(Os::CHROME_OS . ' version is missing');
    }
}
