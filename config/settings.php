<?php

use App\Command\Resolve\Handler\BotCheckHandler;
use App\Entity\Os;

return [
    'cloak' => [
        'block' => [
            'action' => 'curl',
            'localPage' => [
                'url' => '/',
            ],
            'redirect' => [
                'status' => '303',
                'urls' => [
                    'https://f-store.com.ua/ua/p2356853408-shtany-softshell-multikam.html',
                ],
            ],
            'curl' => [
                'urls' => [
                    'https://f-store.com.ua/ua/p2356853408-shtany-softshell-multikam.html',
                ],
            ],
            'handlers' => [
                BotCheckHandler::class,
            ],
        ],
        'allow' => [
            'filters' => [
                [
                    'action' => 'curl',
                    'url' => 'https://f-store.com.ua',
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
                    'url' => 'https://f-store.com.ua',
                    'geo' => [
                        'country' => [
                            'UA',
                            'DE',
                        ],
                    ],
                    'os' => [
                        Os::IOS,
                        Os::ANDROID,
                        Os::OS_X,
                    ],
                ],
            ],
        ],
    ],
];
