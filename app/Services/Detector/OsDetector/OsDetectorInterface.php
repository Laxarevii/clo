<?php

namespace App\Services\Detector\OsDetector;

use App\Entity\Os;
use App\Entity\UserAgent;

interface OsDetectorInterface
{
    public function detect(UserAgent $userAgent): Os;
}
