<?php

namespace Tests\Unit\Services\Detector\BlockedIpDetector;

use App\Services\Detector\BlockedIpDetector\FileBlockedIpDetector;
use PHPUnit\Framework\TestCase;
use App\Common\DTO\Ip;

class FileBlockedIpDetectorTest extends TestCase
{
    private const BLOCKED_IPS_FILE = 'blocked_ips.txt';
    private FileBlockedIpDetector $detector;

    protected function setUp(): void
    {
        // Create a temporary file with blocked IPs for testing
        file_put_contents(self::BLOCKED_IPS_FILE, "192.168.1.1\n10.0.0.1\n");
        $this->detector = new FileBlockedIpDetector(self::BLOCKED_IPS_FILE);
    }

    protected function tearDown(): void
    {
        // Remove the temporary file after testing
        unlink(self::BLOCKED_IPS_FILE);
    }

    public function testIsBlockedIpReturnsTrueForBlockedIp(): void
    {
        $ipMock = $this->createMock(Ip::class);
        $ipMock->method('getValue')->willReturn('192.168.1.1');

        $this->assertTrue($this->detector->isBlockedIp($ipMock));
    }

    public function testIsBlockedIpReturnsFalseForAllowedIp(): void
    {
        $ipMock = $this->createMock(Ip::class);
        $ipMock->method('getValue')->willReturn('192.168.1.2'); // Not in the blocked list

        $this->assertFalse($this->detector->isBlockedIp($ipMock));
    }
}
