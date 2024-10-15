<?php

namespace Tests\Unit\Services\Detector\OsDetector;

use App\Common\DTO\Os;
use App\Common\DTO\UserAgent;
use App\Exceptions\UnknownOSException;
use App\Exceptions\UnknownOsVersionException;
use App\Services\Detector\OsDetector\IosDetector;
use PHPUnit\Framework\TestCase;

class IosDetectorTest extends TestCase
{
    private IosDetector $iosDetector;

    protected function setUp(): void
    {
        $this->iosDetector = new IosDetector();
    }

    public function testDoDetectReturnsIos(): void
    {
        $userAgentValue =
            'Mozilla/5.0 (iPhone; CPU iPhone OS 18_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/129.0.6668.69 Mobile/15E148 Safari/604.1';
        $userAgent = new UserAgent($userAgentValue);
        $expectedOs = new Os(Os::IOS, '18.0');
        $os = $this->iosDetector->detect($userAgent);
        $this->assertEquals($expectedOs, $os);
    }

    public function testGetVersionFromUserAgentThrowsUnknownOSException(): void
    {
        $userAgent = new UserAgent('Unknown User Agent');
        $this->expectException(UnknownOSException::class);
        $this->iosDetector->detect($userAgent);
    }

    public function testGetVersionFromUserAgentThrowsUnknownOSVersionException(): void
    {
        $userAgentValue =
            'Mozilla/5.0 (iPhone; CPU iPhone OS like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1';
        $userAgent = new UserAgent($userAgentValue);

        $this->expectException(UnknownOSVersionException::class);

        $this->iosDetector->detect($userAgent);
    }
}

