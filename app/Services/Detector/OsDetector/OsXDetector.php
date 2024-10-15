<?php

namespace App\Services\Detector\OsDetector;

use App\Common\DTO\Os;
use App\Common\DTO\UserAgent;
use App\Exceptions\UnknownOsVersionException;

class OsXDetector extends AbstractOsDetector
{
    protected function doDetect(UserAgent $userAgent): ?Os
    {
        if (stripos($userAgent->getValue(), 'OS X') !== false) {
            return Os::getOsX($this->getVersionFromUserAgent($userAgent));
        }

        return null;
    }

    /**
     * @throws \App\Exceptions\UnknownOsVersionException
     */
    private function getVersionFromUserAgent(UserAgent $userAgent): string
    {
        if (
            preg_match('/OS X ([\d\._]*)/i', $userAgent->getValue(), $matches) === 1
            && !empty($matches[1])
        ) {
            return str_replace('_', '.', $matches[1]);
        }
        throw new UnknownOsVersionException(Os::OS_X . ' version is missing');
    }
}

