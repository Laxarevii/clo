<?php

namespace App\Services\Detector\LanguageDetector;

use App\Entity\AcceptLanguage;
use App\Entity\Language;

interface LanguageDetectorInterface
{
    public function detect(AcceptLanguage $acceptLanguage): Language;
}
