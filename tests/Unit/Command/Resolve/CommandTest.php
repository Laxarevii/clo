<?php

namespace Tests\Unit\Command\Resolve;

use App\Command\Resolve\Command;
use App\Entity\AcceptLanguage;
use App\Entity\Ip;
use App\Entity\Referer;
use App\Entity\UserAgent;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase
{
    private $acceptLanguageMock;
    private $userAgentMock;
    private $ipMock;
    private $refererMock;
    private $uriMock;

    protected function setUp(): void
    {
        $this->acceptLanguageMock = $this->createMock(AcceptLanguage::class);
        $this->userAgentMock = $this->createMock(UserAgent::class);
        $this->ipMock = $this->createMock(Ip::class);
        $this->refererMock = $this->createMock(Referer::class);
        $this->uriMock = $this->createMock(Uri::class);
    }

    public function testConstructorInitializesProperties(): void
    {
        $command = new Command(
            $this->acceptLanguageMock,
            $this->userAgentMock,
            $this->ipMock,
            $this->refererMock,
            $this->uriMock
        );

        $this->assertInstanceOf(Command::class, $command);
    }

    public function testGetAcceptLanguage(): void
    {
        $command = new Command(
            $this->acceptLanguageMock,
            $this->userAgentMock,
            $this->ipMock,
            $this->refererMock,
            $this->uriMock
        );

        $this->assertSame($this->acceptLanguageMock, $command->getAcceptLanguage());
    }

    public function testGetUserAgent(): void
    {
        $command = new Command(
            $this->acceptLanguageMock,
            $this->userAgentMock,
            $this->ipMock,
            $this->refererMock,
            $this->uriMock
        );

        $this->assertSame($this->userAgentMock, $command->getUserAgent());
    }

    public function testGetIp(): void
    {
        $command = new Command(
            $this->acceptLanguageMock,
            $this->userAgentMock,
            $this->ipMock,
            $this->refererMock,
            $this->uriMock
        );

        $this->assertSame($this->ipMock, $command->getIp());
    }

    public function testGetReferer(): void
    {
        $command = new Command(
            $this->acceptLanguageMock,
            $this->userAgentMock,
            $this->ipMock,
            $this->refererMock,
            $this->uriMock
        );

        $this->assertSame($this->refererMock, $command->getReferer());
    }

    public function testGetUri(): void
    {
        $command = new Command(
            $this->acceptLanguageMock,
            $this->userAgentMock,
            $this->ipMock,
            $this->refererMock,
            $this->uriMock
        );

        $this->assertSame($this->uriMock, $command->getUri());
    }
}
