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
    private OsDetectorInterface $detectorMock;
    private OsDetector $osDetector;

    protected function setUp(): void
    {
        $this->detectorMock = $this->createMock(OsDetectorInterface::class);
        $this->osDetector = new OsDetector($this->detectorMock);
    }

    public function testDetectReturnsCorrectOs(): void
    {
        $userAgent = new UserAgent(
            'Mozilla/5.0 (X11; CrOS x86_64 14410.73.0) ' .
            'AppleWebKit/537.36 (KHTML, like Gecko) ' .
            'Chrome/85.0.4183.121 Safari/537.36'
        );
        $expectedOs = new Os('Chrome OS', '1.0');

        $this->detectorMock
            ->method('detect')
            ->with($userAgent)
            ->willReturn($expectedOs);

        $os = $this->osDetector->detect($userAgent);
        $this->assertEquals($expectedOs, $os);
    }

    public function testDetectThrowsExceptionForUnknownOs(): void
    {
        $userAgent = new UserAgent('Unknown User Agent');

        $this->detectorMock
            ->method('detect')
            ->with($userAgent)
            ->willThrowException(new UnknownOSException("Unknown operating system."));

        $this->expectException(UnknownOSException::class);
        $this->osDetector->detect($userAgent);
    }
}
