<?php

namespace App\Services\Detector\ProxyDetector\Client;

use App\Entity\Ip;

interface ProxyClientInterface
{
    public function isUsingProxy(Ip $ip): bool;
}
