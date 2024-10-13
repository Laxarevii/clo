<?php

namespace Tests\Unit\Command\Resolve\Handler;

use App\Command\Resolve\Command;
use App\Command\Resolve\DTO\BadResponse;
use App\Command\Resolve\DTO\SuccessResponse;
use App\Command\Resolve\Handler\BotCheckHandler;
use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Services\Detector\BotDetector\BotDetectorInterface;
use PHPUnit\Framework\TestCase;
use App\Common\DTO\Ip;

class BotCheckHandlerTest extends TestCase
{
    private $botDetectorMock;
    private $nextHandlerMock;
    private $commandMock;
    private $ipMock;

    protected function setUp(): void
    {
        $this->botDetectorMock = $this->createMock(BotDetectorInterface::class);
        $this->nextHandlerMock = $this->createMock(CheckHandlerInterface::class);
        $this->commandMock = $this->createMock(Command::class);
        $this->ipMock = $this->createMock(Ip::class);

        $this->commandMock->method('getIp')->willReturn($this->ipMock);
    }

    public function testHandleReturnsBadResponseForBotIp(): void
    {
        $this->botDetectorMock->method('isBotIp')->with($this->ipMock)->willReturn(true);

        $handler = new BotCheckHandler($this->botDetectorMock);
        $response = $handler->handle($this->commandMock);

        $this->assertInstanceOf(BadResponse::class, $response);
        $this->assertEquals('Bot Ip', $response->getMessage());
    }

    public function testHandleDelegatesToNextHandlerForNonBotIp(): void
    {
        $this->botDetectorMock->method('isBotIp')->with($this->ipMock)->willReturn(false);
        $this->nextHandlerMock->method('handle')->with($this->commandMock)->willReturn(new SuccessResponse());

        $handler = new BotCheckHandler($this->botDetectorMock);
        $handler->setNext($this->nextHandlerMock);

        $response = $handler->handle($this->commandMock);

        $this->assertInstanceOf(SuccessResponse::class, $response);
    }

    public function testHandleReturnsSuccessResponseIfNoNextHandler(): void
    {
        $this->botDetectorMock->method('isBotIp')->with($this->ipMock)->willReturn(false);

        $handler = new BotCheckHandler($this->botDetectorMock);

        $response = $handler->handle($this->commandMock);

        $this->assertInstanceOf(SuccessResponse::class, $response);
    }
}
