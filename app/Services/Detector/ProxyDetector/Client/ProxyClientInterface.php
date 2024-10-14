<?php

namespace App\Services\Detector\ProxyDetector\Client;

use App\Common\DTO\Ip;

interface ProxyClientInterface
{
    public function isUsingProxy(Ip $ip): bool;
}
