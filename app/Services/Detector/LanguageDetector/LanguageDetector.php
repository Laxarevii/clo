<?php

namespace App\Services\Detector\LanguageDetector;

use App\Common\DTO\AcceptLanguage;
use App\Common\DTO\Language;
use App\Common\DTO\UserAgent;

class LanguageDetector implements LanguageDetectorInterface
{
    public function detect(AcceptLanguage $acceptLanguage): Language
    {
        return new Language(substr($acceptLanguage->getValue(), 0, 2));
    }
}
