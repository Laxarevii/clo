<?php

use App\Common\DTO\Os;

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
                    'https://f-store.com.ua/ua/p1970045995-chehol-dlya-shlema.html',
                    'https://f-store.com.ua/ua/p2222833885-muzhskaya-takticheskaya-panama.html',
                ],
            ],
            'curl' => [
                'urls' => [
                    'https://f-store.com.ua/ua/p2356853408-shtany-softshell-multikam.html',
                    'https://f-store.com.ua/ua/p1970045995-chehol-dlya-shlema.html',
                    'https://f-store.com.ua/ua/p2222833885-muzhskaya-takticheskaya-panama.html',
                ],
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
//                    'browser' => [
//                        'chrome',
//                    ],
//                    'time' => [
//                        'from' => '00:00',
//                        'to' => '12:00',
//                    ],
                ],
            ],
        ],
    ],
];
