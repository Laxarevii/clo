<?php

namespace Tests\Unit\Services\Detector\IspDetector;

use App\Common\DTO\Ip;
use App\Common\DTO\Isp;
use App\Services\Detector\IspDetector\IspDetector;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use GeoIp2\Model\Asn;
use PHPUnit\Framework\TestCase;
class IspDetectorTest extends TestCase
{
    private IspDetector $detector;
    private Reader $readerMock;

    private Asn $asnMock;

    protected function setUp(): void
    {
        // Create a mock for the Reader class
        $this->readerMock = $this->createMock(Reader::class);
        $this->detector = new IspDetector($this->readerMock);
        $this->asnMock = $this->createMock(Asn::class);
    }

    public function testDetectReturnsIspForValidIp(): void
    {
        $ipMock = $this->createMock(Ip::class);
        $ipMock->method('getValue')->willReturn('8.8.8.8'); // Example IP
        $this->readerMock->method('asn')->with('8.8.8.8')->willReturn($this->asnMock);
        $this->asnMock->method('__get')->willReturn('Google LLC');
        $isp = $this->detector->detect($ipMock);

        $this->assertInstanceOf(Isp::class, $isp);
        $this->assertEquals('Google LLC', $isp->getValue());
    }

    public function testDetectThrowsExceptionForInvalidIp(): void
    {
        $ipMock = $this->createMock(Ip::class);
        $ipMock->method('getValue')->willReturn('invalid_ip'); // Invalid IP
        $this->readerMock->method('asn')->willThrowException(new AddressNotFoundException());

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Isp for ip 'invalid_ip' does not exist.");

        $this->detector->detect($ipMock);
    }

    public function testDetectThrowsExceptionForGenericException(): void
    {
        $ipMock = $this->createMock(Ip::class);
        $ipMock->method('getValue')->willReturn('8.8.8.8');
        $this->readerMock->method('asn')->willThrowException(new \Exception('Generic error'));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Isp for ip '8.8.8.8' does not exist.");

        $this->detector->detect($ipMock);
    }
}
