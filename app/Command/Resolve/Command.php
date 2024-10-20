<?php

namespace App\Command\Resolve;

use App\Entity\AcceptLanguage;
use App\Entity\Ip;
use App\Entity\Referer;
use App\Entity\UserAgent;
use GuzzleHttp\Psr7\Uri;

class Command
{
    public function __construct(
        private AcceptLanguage $acceptLanguage,
        private UserAgent $userAgent,
        private Ip $ip,
        private Referer $referer,
        private Uri $uri
    ) {
    }

    /**
     * @return \App\Entity\AcceptLanguage
     */
    public function getAcceptLanguage(): AcceptLanguage
    {
        return $this->acceptLanguage;
    }

    /**
     * @return \App\Entity\UserAgent
     */
    public function getUserAgent(): UserAgent
    {
        return $this->userAgent;
    }

    /**
     * @return \App\Entity\Ip
     */
    public function getIp(): Ip
    {
        return $this->ip;
    }

    /**
     * @return \App\Entity\Referer
     */
    public function getReferer(): Referer
    {
        return $this->referer;
    }

    /**
     * @return \GuzzleHttp\Psr7\Uri
     */
    public function getUri(): Uri
    {
        return $this->uri;
    }
}
