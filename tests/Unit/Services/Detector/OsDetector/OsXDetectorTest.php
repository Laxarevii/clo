<?php

namespace Tests\Unit\Services\Detector\OsDetector;

use App\Common\DTO\UserAgent;
use App\Common\DTO\Os;
use App\Exceptions\UnknownOSException;
use App\Exceptions\UnknownOsVersionException;
use App\Services\Detector\OsDetector\OsXDetector;
use PHPUnit\Framework\TestCase;

class OsXDetectorTest extends TestCase
{
    private OsXDetector $osXDetector;

    protected function setUp(): void
    {
        $this->osXDetector = new OsXDetector();
    }

    public function testDoDetectReturnsOsX(): void
    {
        $userAgentValue = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0.1 Safari/605.1.15';
        $userAgent = new UserAgent($userAgentValue);

        $os = $this->osXDetector->detect($userAgent);

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::OS_X, $os->getName());
        $this->assertEquals('10.15.7', $os->getVersion());
    }

    public function testDoDetectReturnsNullForNonOsX(): void
    {
        $userAgentValue = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36';
        $userAgent = new UserAgent($userAgentValue);

        $this->expectException(UnknownOSException::class);

        $this->osXDetector->detect($userAgent);
    }

    public function testGetVersionFromUserAgentThrowsUnknownOsVersionException(): void
    {
        $userAgentValue = 'Mozilla/5.0 (Macintosh; Intel Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0.1 Safari/605.1.15';
        $userAgent = new UserAgent($userAgentValue);

        $this->expectException(UnknownOsVersionException::class);
        $this->expectExceptionMessage(Os::OS_X . ' version is missing');

        $this->osXDetector->detect($userAgent);
    }

    public function testGetVersionFromUserAgentHandlesUnderscore(): void
    {
        $userAgentValue = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0.1 Safari/605.1.15';
        $userAgent = new UserAgent($userAgentValue);

        $os = $this->osXDetector->detect($userAgent);

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals('10.15.7', $os->getVersion());
    }
}
