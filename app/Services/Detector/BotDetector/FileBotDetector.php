<?php

namespace App\Services\Detector\BotDetector;

use App\Common\DTO\Ip;

class FileBotDetector implements BotDetectorInterface
{
    public function __construct(private string $filePath)
    {
    }

    public function isBotIp(Ip $ip): bool
    {
        $botIps = file($this->filePath, FILE_IGNORE_NEW_LINES);
        return in_array($ip->getValue(), $botIps, true);
    }
}
