<?php

namespace App\Services\Detector\ProxyDetector;

use App\Entity\Ip;

interface ProxyDetectorInterface
{
    public function detect(Ip $ip): bool;
}
