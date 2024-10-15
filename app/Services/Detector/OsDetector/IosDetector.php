<?php

namespace App\Services\Detector\OsDetector;

use App\Common\DTO\Os;
use App\Common\DTO\UserAgent;
use App\Exceptions\UnknownOsVersionException;

class IosDetector extends AbstractOsDetector
{
    protected function doDetect(UserAgent $userAgent): ?Os
    {
        $userAgentValue = $userAgent->getValue();
        if (
            (
                stripos($userAgentValue, 'CPU OS') !== false ||
                stripos($userAgentValue, 'iPhone OS') !== false
            ) &&
            stripos($userAgentValue, 'OS X') !== false
        ) {
            return Os::getIos($this->getVersionFromUserAgent($userAgent));
        }

        return null;
    }

    private function getVersionFromUserAgent(UserAgent $userAgent): string
    {
        if (
            preg_match('/CPU( iPhone)? OS ([\d_]*)/i', $userAgent->getValue(), $matches) === 1
            && !empty($matches[2])
        ) {
            return str_replace('_', '.', $matches[2]);
        }
        //todo  $os->setIsMobile(true);
        throw new UnknownOsVersionException(Os::IOS . ' version is missing');
    }
}
