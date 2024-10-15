<?php

namespace App\Services\Detector\OsDetector;

use App\Common\DTO\Os;
use App\Common\DTO\UserAgent;

class OsDetectorChain
{
    private OsDetectorInterface $chain;

    public function __construct()
    {
        $chromeOsDetector = new ChromeOsDetector();
        $iosDetector = new IosDetector();
        $osXDetector = new OsXDetector();

        $chromeOsDetector->setNext($iosDetector)
            ->setNext($osXDetector);

        $this->chain = $chromeOsDetector;
    }

    /**
     * @throws \App\Exceptions\UnknownOSException
     */
    public function detect(UserAgent $userAgent): Os
    {
        return $this->chain->detect($userAgent);
    }
}
