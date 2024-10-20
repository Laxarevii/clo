<?php

namespace Tests\Unit\Command\Resolve\Handler;

use App\Command\CheckBlock\Interface\CheckHandlerInterface;
use App\Command\Resolve\Block\Handler\AbstractCheckHandler;
use PHPUnit\Framework\TestCase;

class AbstractCheckHandlerTest extends TestCase
{
    private $checkHandlerMock;
    private $nextHandlerMock;

    protected function setUp(): void
    {
        $this->checkHandlerMock = $this->getMockForAbstractClass(AbstractCheckHandler::class);
        $this->nextHandlerMock = $this->createMock(CheckHandlerInterface::class);
    }

    public function testSetNextReturnsHandler(): void
    {
        $result = $this->checkHandlerMock->setNext($this->nextHandlerMock);
        $this->assertSame($this->nextHandlerMock, $result);
    }

    public function testSetNextSetsNextHandler(): void
    {
        $this->checkHandlerMock->setNext($this->nextHandlerMock);

        $reflection = new \ReflectionClass($this->checkHandlerMock);
        $property = $reflection->getProperty('nextHandler');
        $property->setAccessible(true);
        $nextHandler = $property->getValue($this->checkHandlerMock);

        $this->assertSame($this->nextHandlerMock, $nextHandler);
    }

    public function testSetNextCanAcceptNull(): void
    {
        $this->checkHandlerMock->setNext(null);

        $reflection = new \ReflectionClass($this->checkHandlerMock);
        $property = $reflection->getProperty('nextHandler');
        $property->setAccessible(true);
        $nextHandler = $property->getValue($this->checkHandlerMock);

        $this->assertNull($nextHandler);
    }
}
