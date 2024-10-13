<?php

namespace Tests\Unit\Command\Resolve\Handler;

use App\Command\Resolve\Command;
use App\Command\Resolve\DTO\BadResponse;
use App\Command\Resolve\DTO\SuccessResponse;
use App\Command\Resolve\Handler\WithOutRefererCheckHandler;
use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Common\DTO\Referer;
use PHPUnit\Framework\TestCase;

class WithOutRefererCheckHandlerTest extends TestCase
{
    private WithOutRefererCheckHandler $handler;
    private Command $commandMock;
    private $nextHandlerMock;

    protected function setUp(): void
    {
        $this->nextHandlerMock = $this->createMock(CheckHandlerInterface::class);
        $this->commandMock = $this->createMock(Command::class);
        $this->handler = new WithOutRefererCheckHandler(true);
    }

    public function testHandleReturnsBadResponseForEmptyRefererWhenBlocked(): void
    {
        $refererMock = $this->createMock(Referer::class);
        $refererMock->method('getValue')->willReturn('');
        $this->commandMock->method('getReferer')->willReturn($refererMock);

        $response = $this->handler->handle($this->commandMock);
        $this->assertInstanceOf(BadResponse::class, $response);
        $this->assertEquals('No Referer', $response->getMessage());
    }

    public function testHandleDelegatesToNextHandlerForValidReferer(): void
    {
        $refererMock = $this->createMock(Referer::class);
        $refererMock->method('getValue')->willReturn('http://example.com');
        $this->commandMock->method('getReferer')->willReturn($refererMock);

        $this->handler->setNext($this->nextHandlerMock);
        $this->nextHandlerMock->method('handle')->willReturn(new SuccessResponse());

        $response = $this->handler->handle($this->commandMock);
        $this->assertInstanceOf(SuccessResponse::class, $response);
    }

    public function testHandleReturnsSuccessResponseIfNoNextHandler(): void
    {
        $refererMock = $this->createMock(Referer::class);
        $refererMock->method('getValue')->willReturn('http://example.com');
        $this->commandMock->method('getReferer')->willReturn($refererMock);

        $response = $this->handler->handle($this->commandMock);
        $this->assertInstanceOf(SuccessResponse::class, $response);
    }

    public function testHandleDoesNotBlockWhenFlagIsFalse(): void
    {
        $this->handler = new WithOutRefererCheckHandler(false);
        $refererMock = $this->createMock(Referer::class);
        $refererMock->method('getValue')->willReturn('');
        $this->commandMock->method('getReferer')->willReturn($refererMock);

        $response = $this->handler->handle($this->commandMock);
        $this->assertInstanceOf(SuccessResponse::class, $response);
    }
}
