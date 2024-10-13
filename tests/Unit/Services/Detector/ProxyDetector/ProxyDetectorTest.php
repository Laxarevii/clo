<?php

namespace Tests\Unit\Services\Detector\ProxyDetector;

use App\Common\DTO\Ip;
use App\Services\Detector\ProxyDetector\Client\ProxyClientInterface;
use App\Services\Detector\ProxyDetector\ProxyDetector;
use PHPUnit\Framework\TestCase;

class ProxyDetectorTest extends TestCase
{
    private ProxyClientInterface $proxyClientMock;
    private ProxyDetector $proxyDetector;

    protected function setUp(): void
    {
        $this->proxyClientMock = $this->createMock(ProxyClientInterface::class);
        $this->proxyDetector = new ProxyDetector($this->proxyClientMock);
    }

    public function testDetectReturnsTrueWhenUsingProxy(): void
    {
        $ip = new Ip('192.168.0.1');

        $this->proxyClientMock
            ->method('isUsingProxy')
            ->with($ip)
            ->willReturn(true);

        $result = $this->proxyDetector->detect($ip);

        $this->assertTrue($result);
    }

    public function testDetectReturnsFalseWhenNotUsingProxy(): void
    {
        $ip = new Ip('192.168.0.1');

        $this->proxyClientMock
            ->method('isUsingProxy')
            ->with($ip)
            ->willReturn(false);

        $result = $this->proxyDetector->detect($ip);

        $this->assertFalse($result);
    }
}
