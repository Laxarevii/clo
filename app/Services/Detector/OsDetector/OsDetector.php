<?php

namespace App\Services\Detector\OsDetector;

use App\Entity\Os;
use App\Entity\UserAgent;

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
