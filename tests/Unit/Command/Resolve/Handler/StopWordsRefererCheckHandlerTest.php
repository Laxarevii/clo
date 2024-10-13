<?php

namespace Tests\Unit\Command\Resolve\Handler;

use App\Command\Resolve\Command;
use App\Command\Resolve\DTO\BadResponse;
use App\Command\Resolve\DTO\SuccessResponse;
use App\Command\Resolve\Handler\StopWordsRefererCheckHandler;
use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Common\DTO\AcceptLanguage;
use App\Common\DTO\Referer;
use PHPUnit\Framework\TestCase;

class StopWordsRefererCheckHandlerTest extends TestCase
{
    private array $stopWords;
    private StopWordsRefererCheckHandler $handler;
    private Command $commandMock;
    private $nextHandlerMock;

    protected function setUp(): void
    {
        $this->stopWords = ['badword1', 'badword2'];
        $this->handler = new StopWordsRefererCheckHandler($this->stopWords);
        $this->commandMock = $this->createMock(Command::class);
        $this->nextHandlerMock = $this->createMock(CheckHandlerInterface::class);
    }

    public function testHandleReturnsBadResponseForRefererWithStopWord(): void
    {
        $userReferer = 'https://example.com?param=badword1';
        $this->commandMock->method('getReferer')->willReturn(new Referer($userReferer));

        $response = $this->handler->handle($this->commandMock);

        $this->assertInstanceOf(BadResponse::class, $response);
        $this->assertEquals('No Referer', $response->getMessage());
    }

    public function testHandleDelegatesToNextHandlerForAllowedReferer(): void
    {
        $userReferer = 'https://example.com';
        $this->commandMock->method('getReferer')->willReturn(new Referer($userReferer));
        $this->handler->setNext($this->nextHandlerMock);
        $this->nextHandlerMock->method('handle')->willReturn(new SuccessResponse());

        $response = $this->handler->handle($this->commandMock);

        $this->assertInstanceOf(SuccessResponse::class, $response);
    }

    public function testHandleReturnsSuccessResponseIfNoNextHandler(): void
    {
        $userReferer = 'https://example.com';
        $this->commandMock->method('getReferer')->willReturn(new Referer($userReferer));

        $response = $this->handler->handle($this->commandMock);

        $this->assertInstanceOf(SuccessResponse::class, $response);
    }

    public function testHandleReturnsBadResponseIfRefererIsNull(): void
    {
        $this->commandMock->method('getReferer')->willReturn(new Referer());

        $response = $this->handler->handle($this->commandMock);

        $this->assertInstanceOf(SuccessResponse::class, $response);
    }
}
