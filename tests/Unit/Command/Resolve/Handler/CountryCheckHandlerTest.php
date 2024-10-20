<?php

namespace Tests\Unit\Command\Resolve\Handler;

use App\Command\CheckBlock\Interface\CheckHandlerInterface;
use App\Command\Common\DTO\BadResponse;
use App\Command\Common\DTO\SuccessResponse;
use App\Command\Resolve\Command;
use App\Command\Resolve\Handler\CountryCheckHandler;
use App\Entity\Country;
use App\Entity\Ip;
use App\Services\Detector\CountryDetector\CountryDetectorInterface;
use PHPUnit\Framework\TestCase;

class CountryCheckHandlerTest extends TestCase
{
    private $countryDetectorMock;
    private $nextHandlerMock;
    private $commandMock;
    private $ipMock;
    private $countryMock;
    private $allowedCountryCodes = ['US', 'CA'];

    protected function setUp(): void
    {
        $this->countryDetectorMock = $this->createMock(CountryDetectorInterface::class);
        $this->nextHandlerMock = $this->createMock(CheckHandlerInterface::class);
        $this->commandMock = $this->createMock(Command::class);
        $this->ipMock = $this->createMock(Ip::class);
        $this->countryMock = $this->createMock(Country::class);

        $this->commandMock->method('getIp')->willReturn($this->ipMock);
    }

    public function testHandleReturnsBadResponseForBlockedCountry(): void
    {
        $this->countryMock->method('getIsoCode')->willReturn('RU');
        $this->countryDetectorMock->method('detect')->with($this->ipMock)->willReturn($this->countryMock);

        $handler = new CountryCheckHandler($this->allowedCountryCodes, $this->countryDetectorMock);
        $response = $handler->handle($this->commandMock);

        $this->assertInstanceOf(BadResponse::class, $response);
        $this->assertEquals('Blocked Country', $response->getMessage());
    }

    public function testHandleDelegatesToNextHandlerForAllowedCountry(): void
    {
        $this->countryMock->method('getIsoCode')->willReturn('US');
        $this->countryDetectorMock->method('detect')->with($this->ipMock)->willReturn($this->countryMock);
        $this->nextHandlerMock->method('handle')->with($this->commandMock)->willReturn(new SuccessResponse());

        $handler = new CountryCheckHandler($this->allowedCountryCodes, $this->countryDetectorMock);
        $handler->setNext($this->nextHandlerMock);

        $response = $handler->handle($this->commandMock);

        $this->assertInstanceOf(SuccessResponse::class, $response);
    }

    public function testHandleReturnsSuccessResponseIfNoNextHandler(): void
    {
        $this->countryMock->method('getIsoCode')->willReturn('US');
        $this->countryDetectorMock->method('detect')->with($this->ipMock)->willReturn($this->countryMock);

        $handler = new CountryCheckHandler($this->allowedCountryCodes, $this->countryDetectorMock);
        $response = $handler->handle($this->commandMock);

        $this->assertInstanceOf(SuccessResponse::class, $response);
    }
}
