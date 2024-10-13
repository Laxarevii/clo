<?php

namespace App\Services\Detector\BotDetector;

use App\Common\DTO\Ip;

interface BotDetectorInterface
{
    public function isBotIp(Ip $ip): bool;
}
