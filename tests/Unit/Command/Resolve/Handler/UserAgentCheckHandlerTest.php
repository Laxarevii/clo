<?php

namespace Tests\Unit\Command\Resolve\Handler;

use App\Command\Resolve\Command;
use App\Command\Resolve\DTO\BadResponse;
use App\Command\Resolve\DTO\Response;
use App\Command\Resolve\DTO\SuccessResponse;
use App\Command\Resolve\Handler\UserAgentCheckHandler;
use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Common\DTO\UserAgent;
use App\Services\Checker\UserAgentChecker\UserAgentCheckerInterface;
use PHPUnit\Framework\TestCase;

class UserAgentCheckHandlerTest extends TestCase
{
    private UserAgentCheckerInterface $userAgentCheckerMock;
    private UserAgentCheckHandler $handler;
    private Command $commandMock;
    private $nextHandlerMock;

    protected function setUp(): void
    {
        $this->userAgentCheckerMock = $this->createMock(UserAgentCheckerInterface::class);
        $this->handler = new UserAgentCheckHandler($this->userAgentCheckerMock);
        $this->commandMock = $this->createMock(Command::class);
        $this->nextHandlerMock = $this->createMock(CheckHandlerInterface::class);
    }

    public function testHandleReturnsBadResponseForBlockedUserAgent(): void
    {
        $this->commandMock->method('getUserAgent')->willReturn(new UserAgent('blocked-user-agent'));
        $this->userAgentCheckerMock->method('isBlocked')->willReturn(true);
        $response = $this->handler->handle($this->commandMock);
        $this->assertInstanceOf(BadResponse::class, $response);
        $this->assertEquals('UserAgent blocked', $response->getMessage());
    }

    public function testHandleDelegatesToNextHandlerForAllowedUserAgent(): void
    {
        $this->commandMock->method('getUserAgent')->willReturn(new UserAgent('allowed-user-agent'));
        $this->userAgentCheckerMock->method('isBlocked')->willReturn(false);
        $this->handler->setNext($this->nextHandlerMock);
        $this->nextHandlerMock->method('handle')->willReturn(new SuccessResponse());
        $response = $this->handler->handle($this->commandMock);
        $this->assertInstanceOf(SuccessResponse::class, $response);
    }

    public function testHandleReturnsSuccessResponseIfNoNextHandler(): void
    {
        $this->commandMock->method('getUserAgent')->willReturn(new UserAgent('allowed-user-agent'));
        $this->userAgentCheckerMock->method('isBlocked')->willReturn(false);
        $response = $this->handler->handle($this->commandMock);
        $this->assertInstanceOf(SuccessResponse::class, $response);
    }
}
