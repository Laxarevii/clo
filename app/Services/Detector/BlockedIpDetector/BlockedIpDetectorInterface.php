<?php

namespace App\Services\Detector\BlockedIpDetector;

use App\Common\DTO\Ip;

interface BlockedIpDetectorInterface
{
    public function isBlockedIp(Ip $ip): bool;
}
