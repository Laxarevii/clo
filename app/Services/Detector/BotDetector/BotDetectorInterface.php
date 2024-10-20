<?php

namespace App\Services\Detector\BotDetector;

use App\Entity\Ip;

interface BotDetectorInterface
{
    public function isBotIp(Ip $ip): bool;
}
