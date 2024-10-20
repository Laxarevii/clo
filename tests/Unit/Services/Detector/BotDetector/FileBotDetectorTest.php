<?php

namespace Tests\Unit\Services\Detector\BotDetector;

use App\Entity\Ip;
use App\Services\Detector\BotDetector\FileBotDetector;
use PHPUnit\Framework\TestCase;

class FileBotDetectorTest extends TestCase
{
    private const BOT_IPS_FILE = 'bot_ips.txt';
    private FileBotDetector $detector;

    protected function setUp(): void
    {
        // Create a temporary file with bot IPs for testing
        file_put_contents(self::BOT_IPS_FILE, "192.168.1.100\n10.0.0.2\n");
        $this->detector = new FileBotDetector(self::BOT_IPS_FILE);
    }

    protected function tearDown(): void
    {
        // Remove the temporary file after testing
        unlink(self::BOT_IPS_FILE);
    }

    public function testIsBotIpReturnsTrueForBotIp(): void
    {
        $ipMock = $this->createMock(Ip::class);
        $ipMock->method('getValue')->willReturn('192.168.1.100'); // IP in the bot list

        $this->assertTrue($this->detector->isBotIp($ipMock));
    }

    public function testIsBotIpReturnsFalseForAllowedIp(): void
    {
        $ipMock = $this->createMock(Ip::class);
        $ipMock->method('getValue')->willReturn('192.168.1.200'); // IP not in the bot list

        $this->assertFalse($this->detector->isBotIp($ipMock));
    }
}
