<?php

namespace App\Services\Detector\ProxyDetector;

use App\Entity\Ip;
use App\Services\Detector\ProxyDetector\Client\ProxyClientInterface;

class ProxyDetector implements ProxyDetectorInterface
{
    public function __construct(
        private ProxyClientInterface $client
    ) {
    }

    public function detect(Ip $ip): bool
    {
        return $this->client->isUsingProxy($ip);
    }
}
