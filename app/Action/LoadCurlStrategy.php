<?php

namespace App\Action;

class LoadCurlStrategy implements ActionInterface
{
    private string $domain;

    public function __construct(private string $localUrl)
    {
        $this->domain = $this->extractDomainFromUrl($localUrl);
    }

    private function extractDomainFromUrl(string $url): string
    {
        $parsedUrl = parse_url($url);
        return $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
    }

    public function execute()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->localUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $html = curl_exec($ch);

        if (curl_errno($ch)) {
            $errorMessage = curl_error($ch);
            curl_close($ch);
            return response()->json(['error' => $errorMessage], 500);
        }

        curl_close($ch);

        $html = $this->convertRelativeUrlsToAbsolute($html);

        return response($html, 200)->header('Content-Type', 'text/html');
    }

    private function convertRelativeUrlsToAbsolute(string $html): string
    {
        $patterns = [
            '/(href|src)="\/([^"]+)"/i',
            '/(href|src)="(?!https?:\/\/)([^"]+)"/i',
        ];

        $replacements = [
            '$1="' . $this->domain . '/$2"',
            '$1="' . $this->domain . '/$2"',
        ];

        return preg_replace($patterns, $replacements, $html);
    }
}
