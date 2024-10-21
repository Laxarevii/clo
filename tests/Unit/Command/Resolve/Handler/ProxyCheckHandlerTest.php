<?php

namespace Tests\Unit\Command\Resolve\Handler;

use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Command\Common\DTO\BadResponse;
use App\Command\Common\DTO\SuccessResponse;
use App\Command\Resolve\Command;
use App\Command\Resolve\Handler\ProxyCheckHandler;
use App\Services\Detector\ProxyDetector\ProxyDetectorInterface;
use PHPUnit\Framework\TestCase;

class ProxyCheckHandlerTest extends TestCase
{
    private $proxyDetectorMock;
    private $nextHandlerMock;
    private $commandMock;
    private $handler;

    protected function setUp(): void
    {
        $this->proxyDetectorMock = $this->createMock(ProxyDetectorInterface::class);
        $this->nextHandlerMock = $this->createMock(CheckHandlerInterface::class);
        $this->commandMock = $this->createMock(Command::class);
        $this->handler = new ProxyCheckHandler($this->proxyDetectorMock);
    }

    public function testHandleReturnsBadResponseForProxy(): void
    {
        $this->proxyDetectorMock->method('detect')->willReturn(true);

        $response = $this->handler->handle($this->commandMock);

        $this->assertInstanceOf(BadResponse::class, $response);
        $this->assertEquals('Using Proxy', $response->getMessage());
    }

    public function testHandleDelegatesToNextHandlerForNoProxy(): void
    {
        $this->proxyDetectorMock->method('detect')->willReturn(false);
        $this->nextHandlerMock->method('handle')->willReturn(new SuccessResponse());

        $this->handler->setNext($this->nextHandlerMock);
        $response = $this->handler->handle($this->commandMock);

        $this->assertInstanceOf(SuccessResponse::class, $response);
    }
}
