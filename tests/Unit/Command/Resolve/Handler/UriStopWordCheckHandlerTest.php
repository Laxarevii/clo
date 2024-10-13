<?php

namespace Tests\Unit\Command\Resolve\Handler;

use App\Command\Resolve\Command;
use App\Command\Resolve\DTO\BadResponse;
use App\Command\Resolve\DTO\Response;
use App\Command\Resolve\DTO\SuccessResponse;
use App\Command\Resolve\Handler\UriStopWordCheckHandler;
use App\Command\Resolve\Interface\CheckHandlerInterface;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;

class UriStopWordCheckHandlerTest extends TestCase
{
    private array $stopWords;
    private UriStopWordCheckHandler $handler;
    private Command $commandMock;
    private $nextHandlerMock;

    protected function setUp(): void
    {
        $this->stopWords = ['forbidden', 'blocked'];
        $this->handler = new UriStopWordCheckHandler($this->stopWords);
        $this->commandMock = $this->createMock(Command::class);
        $this->nextHandlerMock = $this->createMock(CheckHandlerInterface::class);
    }

    public function testHandleReturnsBadResponseForUriWithStopWord(): void
    {
        $this->commandMock->method('getUri')->willReturn(new Uri('https://example.com/forbidden'));
        $response = $this->handler->handle($this->commandMock);
        $this->assertInstanceOf(BadResponse::class, $response);
        $this->assertEquals('No Referer', $response->getMessage());
    }

    public function testHandleDelegatesToNextHandlerForUriWithoutStopWord(): void
    {
        $this->commandMock->method('getUri')->willReturn(new Uri('https://example.com/allowed'));
        $this->handler->setNext($this->nextHandlerMock);
        $this->nextHandlerMock->method('handle')->willReturn(new SuccessResponse());
        $response = $this->handler->handle($this->commandMock);
        $this->assertInstanceOf(SuccessResponse::class, $response);
    }

    public function testHandleReturnsSuccessResponseIfNoNextHandler(): void
    {
        $this->commandMock->method('getUri')->willReturn(new Uri('https://example.com/allowed'));
        $response = $this->handler->handle($this->commandMock);
        $this->assertInstanceOf(SuccessResponse::class, $response);
    }
}
