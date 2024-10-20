<?php

namespace App\Services\Detector\LanguageDetector;

use App\Entity\AcceptLanguage;
use App\Entity\Language;

class LanguageDetector implements LanguageDetectorInterface
{
    public function detect(AcceptLanguage $acceptLanguage): Language
    {
        return new Language(substr($acceptLanguage->getValue(), 0, 2));
    }
}
