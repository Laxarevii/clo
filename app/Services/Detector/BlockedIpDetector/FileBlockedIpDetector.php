<?php

namespace App\Services\Detector\BlockedIpDetector;

use App\Entity\Ip;

class FileBlockedIpDetector implements BlockedIpDetectorInterface
{
    public function __construct(private string $filePath)
    {
    }

    public function isBlockedIp(Ip $ip): bool
    {
        $blockedIps = file($this->filePath, FILE_IGNORE_NEW_LINES);
        return in_array($ip->getValue(), $blockedIps, true);
    }
}
