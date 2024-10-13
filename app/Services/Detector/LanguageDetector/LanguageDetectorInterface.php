<?php

namespace App\Services\Detector\LanguageDetector;

use App\Common\DTO\AcceptLanguage;
use App\Common\DTO\Language;
use App\Common\DTO\UserAgent;

interface LanguageDetectorInterface
{
    public function detect(AcceptLanguage $acceptLanguage): Language;
}
