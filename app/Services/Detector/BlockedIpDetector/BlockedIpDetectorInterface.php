<?php

namespace App\Services\Detector\BlockedIpDetector;

use App\Entity\Ip;

interface BlockedIpDetectorInterface
{
    public function isBlockedIp(Ip $ip): bool;
}
