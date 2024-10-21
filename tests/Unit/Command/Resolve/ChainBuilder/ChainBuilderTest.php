<?php

namespace Tests\Unit\Command\Resolve\ChainBuilder;

use App\Command\Resolve\ChainBuilder\ChainBuilder;
use App\Command\Resolve\Handler\BotCheckHandler;
use App\Command\Resolve\Handler\IpCheckHandler;
use App\Entity\Os;
use PHPUnit\Framework\TestCase;

class ChainBuilderTest extends TestCase
{
    private ChainBuilder $builder;
    private $conf = [
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
    ];

    public function setUp(): void
    {

    }
}
