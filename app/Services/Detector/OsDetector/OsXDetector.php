<?php

namespace App\Services\Detector\OsDetector;

use App\Common\DTO\Os;
use App\Common\DTO\UserAgent;

class OsXDetector extends AbstractOsDetector
{
    protected function doDetect(UserAgent $userAgent): ?Os
    {
        if (stripos($userAgent->getValue(), 'OS X') !== false) {
            return new Os('OS X', $this->getVersionFromUserAgent($userAgent));
        }

        return null;
    }

    private function getVersionFromUserAgent(UserAgent $userAgent): string
    {
        if (preg_match('/OS X ([\d\._]*)/i', $userAgent->getValue(), $matches)) {
            if (isset($matches[1])) {
                return str_replace('_', '.', $matches[1]);
            }
        }
        throw new \InvalidArgumentException(Os::OS_X . ' version is missing');
    }
}

