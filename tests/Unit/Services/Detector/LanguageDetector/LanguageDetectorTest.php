<?php

namespace Tests\Unit\Services\Detector\LanguageDetector;

use App\Services\Detector\LanguageDetector\LanguageDetector;
use App\Services\Detector\LanguageDetector\LanguageDetectorInterface;
use PHPUnit\Framework\TestCase;
use App\Common\DTO\AcceptLanguage;
use App\Common\DTO\Language;

class LanguageDetectorTest extends TestCase
{
    private LanguageDetectorInterface $languageDetector;

    protected function setUp(): void
    {
        $this->languageDetector = new LanguageDetector();
    }

    public function testDetectReturnsCorrectLanguage(): void
    {
        $acceptLanguage = new AcceptLanguage('en-US,en;q=0.9');

        $detectedLanguage = $this->languageDetector->detect($acceptLanguage);

        $this->assertInstanceOf(Language::class, $detectedLanguage);
        $this->assertEquals('en', $detectedLanguage->getValue());
    }

    public function testDetectWithDifferentLanguage(): void
    {
        $acceptLanguage = new AcceptLanguage('ru-RU,ru;q=0.9');

        $detectedLanguage = $this->languageDetector->detect($acceptLanguage);

        $this->assertEquals('ru', $detectedLanguage->getValue());
    }
}
