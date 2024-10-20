<?php

namespace Tests\Unit\Command\Resolve\Handler;

use App\Command\CheckBlock\Interface\CheckHandlerInterface;
use App\Command\Common\DTO\BadResponse;
use App\Command\Common\DTO\SuccessResponse;
use App\Command\Resolve\Command;
use App\Command\Resolve\Handler\IpCheckHandler;
use App\Entity\Ip;
use App\Services\Detector\BlockedIpDetector\BlockedIpDetectorInterface;
use PHPUnit\Framework\TestCase;

class IpCheckHandlerTest extends TestCase
{
    private $blockedIpDetectorMock;
    private $nextHandlerMock;
    private $commandMock;
    private $ipMock;

    protected function setUp(): void
    {
        $this->blockedIpDetectorMock = $this->createMock(BlockedIpDetectorInterface::class);
        $this->nextHandlerMock = $this->createMock(CheckHandlerInterface::class);
        $this->commandMock = $this->createMock(Command::class);
        $this->ipMock = $this->createMock(Ip::class);

        $this->commandMock->method('getIp')->willReturn($this->ipMock);
    }

    public function testHandleReturnsBadResponseForBlockedIp(): void
    {
        $this->blockedIpDetectorMock->method('isBlockedIp')->with($this->ipMock)->willReturn(true);

        $handler = new IpCheckHandler($this->blockedIpDetectorMock);
        $response = $handler->handle($this->commandMock);

        $this->assertInstanceOf(BadResponse::class, $response);
        $this->assertEquals('Blocked Ip', $response->getMessage());
    }

    public function testHandleDelegatesToNextHandlerForAllowedIp(): void
    {
        $this->blockedIpDetectorMock->method('isBlockedIp')->with($this->ipMock)->willReturn(false);
        $this->nextHandlerMock->method('handle')->with($this->commandMock)->willReturn(new SuccessResponse());

        $handler = new IpCheckHandler($this->blockedIpDetectorMock);
        $handler->setNext($this->nextHandlerMock);

        $response = $handler->handle($this->commandMock);

        $this->assertInstanceOf(SuccessResponse::class, $response);
    }

    public function testHandleReturnsSuccessResponseIfNoNextHandler(): void
    {
        $this->blockedIpDetectorMock->method('isBlockedIp')->with($this->ipMock)->willReturn(false);

        $handler = new IpCheckHandler($this->blockedIpDetectorMock);
        $response = $handler->handle($this->commandMock);

        $this->assertInstanceOf(SuccessResponse::class, $response);
    }
}
