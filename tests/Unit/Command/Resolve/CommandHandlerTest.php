<?php

namespace Tests\Unit\Command\Resolve;

use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Command\Common\DTO\Response;
use App\Command\Resolve\Command;
use App\Command\Resolve\CommandHandler;
use PHPUnit\Framework\TestCase;

class CommandHandlerTest extends TestCase
{
    private $handlerChainMock;
    private $commandMock;
    private $responseMock;

    protected function setUp(): void
    {
        $this->handlerChainMock = $this->createMock(CheckHandlerInterface::class);
        $this->commandMock = $this->createMock(Command::class);
        $this->responseMock = $this->createMock(Response::class);
    }

    public function testHandleDelegatesToHandlerChain(): void
    {
        $this->handlerChainMock
            ->expects($this->once())
            ->method('handle')
            ->with($this->commandMock)
            ->willReturn($this->responseMock);

        $commandHandler = new CommandHandler($this->handlerChainMock);
        $result = $commandHandler->handle($this->commandMock);

        $this->assertSame($this->responseMock, $result);
    }
}
