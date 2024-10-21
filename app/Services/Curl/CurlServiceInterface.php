<?php

namespace App\Services\Curl;

interface CurlServiceInterface
{
    public function execute(string $url): string;
}