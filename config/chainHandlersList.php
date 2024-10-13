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

return [
    'chain_handlers' => [
        BotCheckHandler::class,
        OsCheckHandler::class,
        IpCheckHandler::class,
        ProxyCheckHandler::class,
        WithOutRefererCheckHandler::class,
        StopWordsRefererCheckHandler::class,
        IspCheckHandler::class,
        LanguageCheckHandler::class,
        CountryCheckHandler::class,
        UriShouldContainCheckHandler::class,
        UriStopWordCheckHandler::class,
        UserAgentCheckHandler::class,
    ],
];
