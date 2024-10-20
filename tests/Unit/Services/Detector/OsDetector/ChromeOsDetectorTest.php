<?php

namespace Tests\Unit\Services\Detector\OsDetector;

use App\Entity\Os;
use App\Entity\UserAgent;
use App\Exceptions\UnknownOSException;
use App\Exceptions\UnknownOsVersionException;
use App\Services\Detector\OsDetector\ChromeOsDetector;
use PHPUnit\Framework\TestCase;

class ChromeOsDetectorTest extends TestCase
{
    private ChromeOsDetector $chromeOsDetector;

    protected function setUp(): void
    {
        $this->chromeOsDetector = new ChromeOsDetector();
    }

    public function testGetVersionFromUserAgentReturnsVersion(): void
    {
        $userAgentValue = 'Mozilla/5.0 (X11; CrOS x86_64 14571.49.0) ' .
            'AppleWebKit/537.36 (KHTML, like Gecko) ' .
            'Chrome/89.0.4389.82 Safari/537.36';
        $userAgent = new UserAgent($userAgentValue);

        $os = $this->chromeOsDetector->detect($userAgent);

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals('Chrome OS', $os->getName());
        $this->assertEquals('89.0.4389.82', $os->getVersion());
    }

    public function testGetVersionFromUserAgentThrowsUnknownOsVersionException(): void
    {
        $userAgentValue = 'Mozilla/5.0 (Linux; CrOS x86_64 14571.49.0) ' .
            'AppleWebKit/537.36 (KHTML, like Gecko) ' .
            'Safari/537.36';
        $userAgent = new UserAgent($userAgentValue);

        $this->expectException(UnknownOsVersionException::class);
        $this->expectExceptionMessage('Chrome OS version is missing');

        $this->chromeOsDetector->detect($userAgent);
    }

    public function testDoDetectUnknownOSException(): void
    {
        $userAgentValue = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) ' .
            'AppleWebKit/537.36 (KHTML, like Gecko) ' .
            'Chrome/89.0.4389.82 Safari/537.36';
        $userAgent = new UserAgent($userAgentValue);

        $this->expectException(UnknownOSException::class);
        $this->chromeOsDetector->detect($userAgent);
    }

    public function testDoDetectHandlesInvalidVersionFormat(): void
    {
        $userAgentValue = 'Mozilla/5.0 (X11; CrOS x86_64 14571.49.0) ' .
            'AppleWebKit/537.36 (KHTML, like Gecko) ' .
            'Chrome/invalid.version Safari/537.36';
        $userAgent = new UserAgent($userAgentValue);

        $this->expectException(UnknownOsVersionException::class);
        $this->expectExceptionMessage('Chrome OS version is missing');

        $this->chromeOsDetector->detect($userAgent);
    }
}
