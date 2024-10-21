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
                    'action' => 'redirect',
                    'url' => 'https://onlyfans.com/namanicks',
//                    'geo' => [
//                        'country' => [
//                            'US',
//                            'DE',
//                        ],
//                    ],
                    'os' => [
                        Os::IOS,
                        Os::ANDROID,
                        Os::OS_X,
                    ],
                ],
                [
                    'action' => 'redirect',
                    'url' => 'https://onlyfans.com/jessiemay_free',
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
            ],
        ],
    ],
];
