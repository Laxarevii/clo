<?php

namespace App\Action;

class LoadCurlStrategy implements ActionInterface
{
    public function __construct(
        private string $localUrl,
    ) {
    }

    public function execute()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->localUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $html = curl_exec($ch);

        if (curl_errno($ch)) {
            $errorMessage = curl_error($ch);
            return response()->json(['error' => $errorMessage], 500);
        }

        curl_close($ch);

        return response($html, 200)->header('Content-Type', 'text/html');
    }
}
