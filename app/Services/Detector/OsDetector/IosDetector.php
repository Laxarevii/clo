<?php

namespace App\Services\Detector\OsDetector;

use App\Common\DTO\Os;
use App\Common\DTO\UserAgent;

class IosDetector extends AbstractOsDetector
{
    protected function doDetect(UserAgent $userAgent): ?Os
    {
        $userAgentValue = $userAgent->getValue();
        if ((stripos($userAgentValue, 'CPU OS') !== false ||
                stripos($userAgentValue, 'iPhone OS') !== false) &&
            stripos($userAgentValue, 'OS X') === false) {
            return new Os('iOS', $this->getVersionFromUserAgent($userAgent));
        }

        return null;
    }

    private function getVersionFromUserAgent(UserAgent $userAgent): string
    {
        if (preg_match('/CPU( iPhone)? OS ([\d_]*)/i', $userAgent->getValue(), $matches)) {
           return str_replace('_', '.', $matches[2]);
        }
        //todo  $os->setIsMobile(true);
        throw new \InvalidArgumentException(self::IOS . ' version is missing');
    }
}