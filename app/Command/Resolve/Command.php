<?php

namespace App\Command\Resolve;

use App\Common\DTO\AcceptLanguage;
use App\Common\DTO\Ip;
use App\Common\DTO\Referer;
use App\Common\DTO\UserAgent;
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
     * @return \App\Common\DTO\AcceptLanguage
     */
    public function getAcceptLanguage(): AcceptLanguage
    {
        return $this->acceptLanguage;
    }

    /**
     * @return \App\Common\DTO\UserAgent
     */
    public function getUserAgent(): UserAgent
    {
        return $this->userAgent;
    }

    /**
     * @return \App\Common\DTO\Ip
     */
    public function getIp(): Ip
    {
        return $this->ip;
    }

    /**
     * @return \App\Common\DTO\Referer
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
