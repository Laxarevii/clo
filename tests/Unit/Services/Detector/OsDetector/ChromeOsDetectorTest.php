<?php

namespace Tests\Unit\Services\Detector\OsDetector;

use App\Common\DTO\Os;
use App\Common\DTO\UserAgent;
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

    public function testDoDetectReturnsChromeOs(): void
    {
        $userAgent = new UserAgent('Mozilla/5.0 (X11; CrOS x86_64 14410.73.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36');
        $expectedOs = new Os(Os::CHROME_OS, '85.0.4183.121');
        $os = $this->chromeOsDetector->detect($userAgent);
        $this->assertEquals($expectedOs, $os);
    }

    public function testGetVersionFromUserAgentThrowsExceptionForMissingVersion(): void
    {
        $userAgent = new UserAgent('Mozilla/5.0 (X11; CrOS x86_64 14410.73.0) AppleWebKit/537.36 (KHTML, like Gecko) Safari/537.36');
        $this->expectException(UnknownOsVersionException::class);
        $this->expectExceptionMessage(Os::CHROME_OS . ' version is missing');
        $this->chromeOsDetector->detect($userAgent);
    }
}
