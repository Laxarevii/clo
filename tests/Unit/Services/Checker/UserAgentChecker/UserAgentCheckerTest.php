<?php

namespace Tests\Unit\Services\Checker\UserAgentChecker;

use App\Entity\UserAgent;
use App\Services\Checker\UserAgentChecker\UserAgentChecker;
use PHPUnit\Framework\TestCase;

class UserAgentCheckerTest extends TestCase
{
    private UserAgentChecker $checker;

    protected function setUp(): void
    {
        $this->checker = new UserAgentChecker(['BadBot', 'AnotherBadBot']);
    }

    public function testIsBlockedReturnsTrueForBlockedUserAgent(): void
    {
        $userAgentMock = $this->createMock(UserAgent::class);
        $userAgentMock->method('getValue')->willReturn('Mozilla/5.0 (compatible; BadBot/1.0)');

        $this->assertTrue($this->checker->isBlocked($userAgentMock));
    }

    public function testIsBlockedReturnsFalseForAllowedUserAgent(): void
    {
        $userAgentMock = $this->createMock(UserAgent::class);
        $userAgentMock->method('getValue')->willReturn(
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) ' .
            'AppleWebKit/537.36 (KHTML, like Gecko) ' .
            'Chrome/91.0.4472.124 Safari/537.36'
        );

        $this->assertFalse($this->checker->isBlocked($userAgentMock));
    }
}
