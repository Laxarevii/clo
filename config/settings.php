<?php

use App\Command\Resolve\Handler\BotCheckHandler;
use App\Command\Resolve\Handler\IpCheckHandler;
use App\Entity\Os;

return [
    'cloak' => [
        'block' => [
            'action' => 'curl',
            'url' =>
                'https://google.com',
            'handlers' => [
                BotCheckHandler::class,
                IpCheckHandler::class,
            ],
        ],
        'allow' => [
            'filters' => [
                [
                    'action' => 'curl',
                    'url' => 'https://www.blackorange.com.ua/login',
                    'geo' => [
                        'country' => [
                            'UA',
                            'DE',
                        ],
                    ],
                    'os' => [
                        Os::IOS,
                        Os::ANDROID,
                    ],
                ],
                [
                    'action' => 'curl',
                    'url' => 'https://www.blackorange.com.ua/',
                    'geo' => [
                        'country' => [
                            'UA',
                            'DE',
                        ],
                    ],
                    'os' => [
                        Os::IOS,
                        Os::ANDROID,
//                        Os::OS_X,
                    ],
                ],
            ],
        ],
    ],
];
