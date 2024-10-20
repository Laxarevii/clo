<?php

use App\Services\Detector\OsDetector\ChromeOsDetector;
use App\Services\Detector\OsDetector\IosDetector;
use App\Services\Detector\OsDetector\OsXDetector;

return [
    'chainDetectors' => [
        // Chrome OS before OS X
        ChromeOsDetector::class,
        // iOS before OS X
        IosDetector::class,
        OsXDetector::class,
    ],
];
