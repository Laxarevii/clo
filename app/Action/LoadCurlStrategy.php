<?php

namespace App\Action;

use App\Services\Curl\CurlServiceInterface;
use Exception;
use Illuminate\Http\Response;

class LoadCurlStrategy implements ActionInterface
{
    private string $domain;
    private CurlServiceInterface $curlService;

    public function __construct(private string $localUrl, CurlServiceInterface $curlService)
    {
        $this->domain = $this->extractDomainFromUrl($localUrl);
        $this->curlService = $curlService;
    }

    private function extractDomainFromUrl(string $url): string
    {
        $parsedUrl = parse_url($url);
        return $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
    }

    public function execute(): Response
    {
        try {
            $html = $this->curlService->execute($this->localUrl);
        } catch (Exception $e) {
            // Создаем ответ с кодом 500
            return new Response($e->getMessage(), 500, ['Content-Type' => 'text/html']);
        }

        $html = $this->convertRelativeUrlsToAbsolute($html);

        return new Response($html, 200, ['Content-Type' => 'text/html']);
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
