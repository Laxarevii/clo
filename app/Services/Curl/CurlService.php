<?php

namespace App\Services\Curl;

use Exception;

class CurlService implements CurlServiceInterface
{
    public function execute(string $url): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $html = curl_exec($ch);

        if (curl_errno($ch)) {
            $errorMessage = curl_error($ch);
            curl_close($ch);
            throw new Exception($errorMessage);
        }

        curl_close($ch);

        return $html;
    }
}