<?php

namespace Tests\Unit\Services\Detector\CountryDetector;

use App\Entity\Country;
use App\Entity\Ip;
use App\Services\Detector\CountryDetector\CountryDetector;
use GeoIp2\Exception\AddressNotFoundException;
use GeoIp2\Model\Country as CountryModel;
use GeoIp2\ProviderInterface;
use GeoIp2\Record\Country as CountryRecord;
use PHPUnit\Framework\TestCase;

class CountryDetectorTest extends TestCase
{
    private CountryDetector $detector;
    private $countryProviderMock;

    protected function setUp(): void
    {
        $this->countryProviderMock = $this->createMock(ProviderInterface::class);
        $this->detector = new CountryDetector($this->countryProviderMock);
    }

    public function testDetectReturnsCountryForValidIp(): void
    {
        $ipMock = $this->createMock(Ip::class);
        $ipMock->method('getValue')->willReturn('8.8.8.8');
        $country = $this->createMock(CountryModel::class);
        $iso = $this->createMock(CountryRecord::class);

        $iso->method('__get')
            ->with('isoCode')
            ->willReturn('US');
        $country->method('__get')
            ->with('country')
            ->willReturn($iso);
        $this->countryProviderMock->method('country')
            ->willReturn($country);

        $country = $this->detector->detect($ipMock);

        $this->assertInstanceOf(Country::class, $country);
        $this->assertEquals('US', $country->getIsoCode());
    }

    public function testDetectThrowsExceptionForInvalidIp(): void
    {
        $ipMock = $this->createMock(Ip::class);
        $ipMock->method('getValue')->willReturn('invalid_ip'); // Invalid IP
        $this->countryProviderMock->method('country')->willThrowException(new AddressNotFoundException());

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Country for ip 'invalid_ip' does not exist.");

        $this->detector->detect($ipMock);
    }
}
