<?php

namespace Tests\Unit\Services\Detector\OsDetector;

use App\Common\DTO\Os;
use App\Common\DTO\UserAgent;
use App\Exceptions\UnknownOSException;
use App\Services\Detector\OsDetector\OsDetector;
use PHPUnit\Framework\TestCase;

class OsDetectorTest extends TestCase
{
    private OsDetector $osDetector;

    protected function setUp(): void
    {
        $this->osDetector = new OsDetector();
    }

    /**
     * @throws \App\Exceptions\UnknownOSException
     */
    public function testDetectChromeOs(): void
    {
        $userAgent = new UserAgent('Mozilla/5.0 (X11; CrOS x86_64 13099.45.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36');

        $detectedOs = $this->osDetector->detect($userAgent);

        $this->assertInstanceOf(Os::class, $detectedOs);
        $this->assertEquals('Chrome OS', $detectedOs->getValue());
    }

    public function testDetectIOS(): void
    {
        $userAgent = new UserAgent('Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15A372 Safari/604.1');

        $detectedOs = $this->osDetector->detect($userAgent);

        $this->assertInstanceOf(Os::class, $detectedOs);
        $this->assertEquals('iOS', $detectedOs->getValue());
    }

    public function testDetectOSX(): void
    {
        $userAgent = new UserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0.3 Safari/605.1.15');

        $detectedOs = $this->osDetector->detect($userAgent);

        $this->assertInstanceOf(Os::class, $detectedOs);
        $this->assertEquals('OS X', $detectedOs->getValue());
    }

    public function testDetectUnknownOSExceptionThrowsException(): void
    {
        $this->expectException(UnknownOSException::class);

        $userAgent = new UserAgent('Mozilla/5.0 (Unknown OS) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36');

        $this->osDetector->detect($userAgent);
    }
}
