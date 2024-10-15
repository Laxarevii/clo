<?php

namespace Tests\Unit\Command\Resolve\Handler;

namespace Tests\Unit\Command\Resolve\Handler;

use App\Command\Resolve\Command;
use App\Command\Resolve\DTO\BadResponse;
use App\Command\Resolve\DTO\SuccessResponse;
use App\Command\Resolve\Handler\OsCheckHandler;
use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Common\DTO\Os;
use App\Common\DTO\UserAgent;
use App\Services\Detector\OsDetector\OsDetectorInterface;
use PHPUnit\Framework\TestCase;

class OsCheckHandlerTest extends TestCase
{
    private $osDetectorMock;
    private $nextHandlerMock;
    private $commandMock;
    private $osMock;
    private $getUserAgent;
    private array $allowedOses = [
        Os::WINDOWS,
        Os::LINUX,
    ];

    protected function setUp(): void
    {
        $this->osDetectorMock = $this->createMock(OsDetectorInterface::class);
        $this->nextHandlerMock = $this->createMock(CheckHandlerInterface::class);
        $this->commandMock = $this->createMock(Command::class);
        $this->osMock = $this->createMock(Os::class);
        $this->getUserAgent = $this->createMock(UserAgent::class);

        $this->commandMock->method('getUserAgent')->willReturn($this->getUserAgent);
    }

    public function testHandleReturnsBadResponseForForbiddenOs(): void
    {
        $this->osMock->method('getName')->willReturn(Os::OS_X);
        $this->osDetectorMock->method('detect')->with($this->commandMock->getUserAgent())->willReturn($this->osMock);

        $handler = new OsCheckHandler($this->allowedOses, $this->osDetectorMock);
        $response = $handler->handle($this->commandMock);

        $this->assertInstanceOf(BadResponse::class, $response);
        $this->assertEquals('Forbidden OS', $response->getMessage());
    }

    public function testHandleDelegatesToNextHandlerForAllowedOs(): void
    {
        $this->osMock->method('getName')->willReturn(Os::WINDOWS);
        $this->osDetectorMock->method('detect')->with($this->commandMock->getUserAgent())->willReturn($this->osMock);
        $this->nextHandlerMock->method('handle')->with($this->commandMock)->willReturn(new SuccessResponse());

        $handler = new OsCheckHandler($this->allowedOses, $this->osDetectorMock);
        $handler->setNext($this->nextHandlerMock);

        $response = $handler->handle($this->commandMock);

        $this->assertInstanceOf(SuccessResponse::class, $response);
    }

    public function testHandleReturnsSuccessResponseIfNoNextHandler(): void
    {
        $this->osMock->method('getName')->willReturn(Os::LINUX);
        $this->osDetectorMock->method('detect')->with($this->commandMock->getUserAgent())->willReturn($this->osMock);

        $handler = new OsCheckHandler($this->allowedOses, $this->osDetectorMock);
        $response = $handler->handle($this->commandMock);

        $this->assertInstanceOf(SuccessResponse::class, $response);
    }
}
