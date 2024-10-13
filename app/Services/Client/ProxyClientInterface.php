<?php

namespace App\Services\Client;

use App\Common\DTO\Ip;

interface ProxyClientInterface
{
    public function isUsingProxy(Ip $ip);
}
