<?php

namespace App\Command\Resolve\Block;

use App\Entity\Ip;

class Command
{
    public function __construct(
        private Ip $ip,
    ) {
    }

    /**
     * @return \App\Entity\Ip
     */
    public function getIp(): Ip
    {
        return $this->ip;
    }
}
