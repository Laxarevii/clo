<?php

namespace App\Services\Detector\OsDetector;

use App\Common\DTO\Language;
use App\Common\DTO\Os;
use App\Common\DTO\UserAgent;

interface OsDetectorInterface
{
    public function detectName(UserAgent $userAgent): string;
}
