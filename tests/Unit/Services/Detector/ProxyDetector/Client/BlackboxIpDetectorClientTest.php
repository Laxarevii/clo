<?php

namespace Tests\Unit\Services\Detector\ProxyDetector\Client;

use App\Common\DTO\Ip;
use App\Services\Detector\ProxyDetector\Client\BlackboxIpDetectorClient;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class BlackboxIpDetectorClientTest extends TestCase
{
    private ClientInterface $clientMock;
    private BlackboxIpDetectorClient $proxyClient;
    private Ip $ipMock;

    protected function setUp(): void
    {
        $this->clientMock = $this->createMock(ClientInterface::class);
        $this->proxyClient = new BlackboxIpDetectorClient($this->clientMock);
        $this->ipMock = new Ip('89.209.96.158');
    }

    public function testIsUsingProxyReturnsTrueWhenResponseIsY(): void
    {
        $streamMock = $this->createMock(StreamInterface::class);
        $streamMock->method('getContents')->willReturn('Y');

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getBody')->willReturn($streamMock);

        $this->clientMock->method('request')->willReturn($responseMock);

        $result = $this->proxyClient->isUsingProxy($this->ipMock);

        $this->assertTrue($result);
    }

    public function testIsUsingProxyReturnsFalseWhenResponseIsNotY(): void
    {
        $streamMock = $this->createMock(StreamInterface::class);
        $streamMock->method('getContents')->willReturn('N');

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getBody')->willReturn($streamMock);

        $this->clientMock->method('request')->willReturn($responseMock);

        $result = $this->proxyClient->isUsingProxy($this->ipMock);

        $this->assertFalse($result);
    }
}
