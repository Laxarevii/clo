<?php

namespace App\Services\Client;

use App\Common\DTO\Ip;
use Psr\Http\Client\ClientInterface;

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
        $res = (string)$res->getBody();

        return $res === 'Y';
    }
}
