<?php

namespace App\Services\Detector\OsDetector;

use App\Common\DTO\Os;
use App\Common\DTO\UserAgent;
use App\Exceptions\UnknownOSException;

class OsDetector implements OsDetectorInterface
{
    public function __construct(private OsDetectorInterface $detectorChain)
    {
    }

    public function detect(UserAgent $userAgent): Os
    {
        return $this->detectorChain->detect($userAgent);
    }
}
