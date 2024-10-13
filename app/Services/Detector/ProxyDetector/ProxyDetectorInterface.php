<?php

namespace App\Services\Detector\ProxyDetector;

use App\Common\DTO\Ip;

interface ProxyDetectorInterface
{
    public function detect(Ip $ip): bool;
}
