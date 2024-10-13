<?php

namespace App\Services\Detector\ProxyDetector;

use App\Common\DTO\Ip;
use App\Services\Client\ProxyClientInterface;

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
