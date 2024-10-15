<?php

namespace App\Services\Detector\OsDetector;

use App\Common\DTO\Os;
use App\Common\DTO\UserAgent;
use App\Exceptions\UnknownOSException;
use App\Exceptions\UnknownOsVersionException;

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

    /**
     * @throws \App\Exceptions\UnknownOsVersionException
     */
    private function getVersionFromUserAgent(UserAgent $userAgent): string
    {
        if (preg_match('/Chrome\/([\d\.]*)/i', $userAgent->getValue(), $matches) === 1) {
            return $matches[1];
        }
        throw new UnknownOsVersionException(Os::CHROME_OS . ' version is missing');
    }
}
