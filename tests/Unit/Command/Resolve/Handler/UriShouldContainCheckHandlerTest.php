<?php

namespace Tests\Unit\Command\Resolve\Handler;

use App\Command\CheckBlock\Interface\CheckHandlerInterface;
use App\Command\Common\DTO\BadResponse;
use App\Command\Common\DTO\SuccessResponse;
use App\Command\Resolve\Command;
use App\Command\Resolve\Handler\UriShouldContainCheckHandler;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;

class UriShouldContainCheckHandlerTest extends TestCase
{
    private array $requiredWords;
    private UriShouldContainCheckHandler $handler;
    private Command $commandMock;
    private $nextHandlerMock;

    protected function setUp(): void
    {
        $this->requiredWords = ['example', 'test'];
        $this->handler = new UriShouldContainCheckHandler($this->requiredWords);
        $this->commandMock = $this->createMock(Command::class);
        $this->nextHandlerMock = $this->createMock(CheckHandlerInterface::class);
    }

    public function testHandleReturnsBadResponseForUriWithoutRequiredWords(): void
    {
        $this->commandMock->method('getUri')->willReturn(new Uri('https://example.com'));
        $response = $this->handler->handle($this->commandMock);
        $this->assertInstanceOf(BadResponse::class, $response);
        $this->assertEquals('URI does not contain words', $response->getMessage());
    }

    public function testHandleDelegatesToNextHandlerForUriWithRequiredWords(): void
    {
        $this->commandMock->method('getUri')->willReturn(new Uri('https://example.com/test'));
        $this->handler->setNext($this->nextHandlerMock);
        $this->nextHandlerMock->method('handle')->willReturn(new SuccessResponse());
        $response = $this->handler->handle($this->commandMock);
        $this->assertInstanceOf(SuccessResponse::class, $response);
    }

    public function testHandleReturnsSuccessResponseIfNoNextHandler(): void
    {
        $this->commandMock->method('getUri')->willReturn(new Uri('https://example.com/test'));
        $response = $this->handler->handle($this->commandMock);
        $this->assertInstanceOf(SuccessResponse::class, $response);
    }
}
