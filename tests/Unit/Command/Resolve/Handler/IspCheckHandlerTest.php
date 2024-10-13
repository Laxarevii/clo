<?php

namespace Tests\Unit\Command\Resolve\Handler;

use App\Command\Resolve\Command;
use App\Command\Resolve\DTO\BadResponse;
use App\Command\Resolve\DTO\SuccessResponse;
use App\Command\Resolve\Handler\IspCheckHandler;
use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Common\DTO\Ip;
use App\Common\DTO\Isp;
use App\Services\Detector\IspDetector\IspDetectorInterface;
use PHPUnit\Framework\TestCase;

class IspCheckHandlerTest extends TestCase
{
    private $ispDetectorMock;
    private $nextHandlerMock;
    private $commandMock;
    private $ipMock;
    private $ispMock;
    private $blockedIsps = ['BlockedIsp'];

    protected function setUp(): void
    {
        $this->ispDetectorMock = $this->createMock(IspDetectorInterface::class);
        $this->nextHandlerMock = $this->createMock(CheckHandlerInterface::class);
        $this->commandMock = $this->createMock(Command::class);
        $this->ipMock = $this->createMock(Ip::class);
        $this->ispMock = $this->createMock(Isp::class);

        $this->commandMock->method('getIp')->willReturn($this->ipMock);
    }

    public function testHandleReturnsBadResponseForBlockedIsp(): void
    {
        $this->ispMock->method('getValue')->willReturn('BlockedIsp');
        $this->ispDetectorMock->method('detect')->with($this->ipMock)->willReturn($this->ispMock);

        $handler = new IspCheckHandler($this->blockedIsps, $this->ispDetectorMock);
        $response = $handler->handle($this->commandMock);

        $this->assertInstanceOf(BadResponse::class, $response);
        $this->assertEquals('Blocked ISP', $response->getMessage());
    }

    public function testHandleDelegatesToNextHandlerForAllowedIsp(): void
    {
        $this->ispMock->method('getValue')->willReturn('AllowedIsp');
        $this->ispDetectorMock->method('detect')->with($this->ipMock)->willReturn($this->ispMock);
        $this->nextHandlerMock->method('handle')->with($this->commandMock)->willReturn(new SuccessResponse());

        $handler = new IspCheckHandler($this->blockedIsps, $this->ispDetectorMock);
        $handler->setNext($this->nextHandlerMock);

        $response = $handler->handle($this->commandMock);

        $this->assertInstanceOf(SuccessResponse::class, $response);
    }

    public function testHandleReturnsSuccessResponseIfNoNextHandler(): void
    {
        $this->ispMock->method('getValue')->willReturn('AllowedIsp');
        $this->ispDetectorMock->method('detect')->with($this->ipMock)->willReturn($this->ispMock);

        $handler = new IspCheckHandler($this->blockedIsps, $this->ispDetectorMock);
        $response = $handler->handle($this->commandMock);

        $this->assertInstanceOf(SuccessResponse::class, $response);
    }
}
