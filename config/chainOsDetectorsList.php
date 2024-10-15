<?php

use App\Command\Resolve\Handler\CountryCheckHandler;
use App\Command\Resolve\Handler\IpCheckHandler;
use App\Command\Resolve\Handler\IspCheckHandler;
use App\Command\Resolve\Handler\LanguageCheckHandler;
use App\Command\Resolve\Handler\StopWordsRefererCheckHandler;
use App\Command\Resolve\Handler\BotCheckHandler;
use App\Command\Resolve\Handler\OsCheckHandler;
use App\Command\Resolve\Handler\ProxyCheckHandler;
use App\Command\Resolve\Handler\UriShouldContainCheckHandler;
use App\Command\Resolve\Handler\UriStopWordCheckHandler;
use App\Command\Resolve\Handler\UserAgentCheckHandler;
use App\Command\Resolve\Handler\WithOutRefererCheckHandler;
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
