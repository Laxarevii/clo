<?php

namespace Tests\Unit\Command\Resolve\ChainBuilder;

use App\Command\Resolve\ChainBuilder\ChainBuilder;
use App\Entity\Os;
use PHPUnit\Framework\TestCase;

class ChainBuilderTest extends TestCase
{
    private ChainBuilder $builder;
    private $conf = [
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
                    'curl' => 'https://f-store.com.ua',
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
                    'browser' => [
                        'chrome',
                    ],
                    'time' => [
                        'from' => '00:00',
                        'to' => '12:00',
                    ],
                ],
            ],
        ],
    ];

    public function setUp(): void
    {
        $this->builder = new ChainBuilder(
            $this->conf
        );
    }

    public function testChain()
    {
        $this->builder->build();
        $this->assertInstanceOf(ChainBuilder::class, ChainBuilder::class);
    }
}
