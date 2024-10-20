<?php

namespace App\Services\Detector\ProxyDetector\Client;

use App\Entity\Ip;
use GuzzleHttp\ClientInterface;

class BlackboxIpDetectorClient implements ProxyClientInterface
{
    public function __construct(
        private ClientInterface $client,
        private string $url = 'https://blackbox.ipinfo.app/lookup/',
    ) {
    }

    public function isUsingProxy(Ip $ip): bool
    {
        $url = $this->url . $ip->getValue();
        $res = $this->client->request('GET', $url);
        $res = $res->getBody()->getContents();

        return $res === 'Y';
    }
}
