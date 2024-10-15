<?php

namespace Tests\Unit\Services\Detector\OsDetector;

use App\Common\DTO\Os;
use App\Common\DTO\UserAgent;
use App\Exceptions\UnknownOSException;
use App\Services\Detector\OsDetector\OsDetector;
use App\Services\Detector\OsDetector\OsDetectorInterface;
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
        $userAgent =
            new UserAgent('Mozilla/5.0 (X11; CrOS x86_64 13099.45.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36');

        $detectedOsName = $this->osDetector->detectName($userAgent);

        $this->assertEquals(Os::CHROME_OS, $detectedOsName);
    }

    public function testDetectIOS(): void
    {
        $userAgent =
            new UserAgent('Mozilla/5.0 (iPhone; CPU iPhone OS 18_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/129.0.6668.69 Mobile/15E148 Safari/604.1');

        $detectedOsName = $this->osDetector->detectName($userAgent);

        $this->assertEquals(Os::IOS, $detectedOsName);
    }

    /**
     * @throws \App\Exceptions\UnknownOSException
     */
    public function testDetectOSX(): void
    {
        $userAgent =
            new UserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0.3 Safari/605.1.15');

        $detectedOsName = $this->osDetector->detectName($userAgent);

        $this->assertEquals(Os::OS_X, $detectedOsName);
    }

    public function testDetectUnknownOSExceptionThrowsException(): void
    {
        $this->expectException(UnknownOSException::class);

        $userAgent =
            new UserAgent('Mozilla/5.0 (Unknown OS) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36');

       $this->osDetector->detectName($userAgent);
    }
}
