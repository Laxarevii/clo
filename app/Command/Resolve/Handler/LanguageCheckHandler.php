<?php

namespace App\Command\Resolve\Handler;

use App\Command\Resolve\Command;
use App\Command\Resolve\DTO\BadResponse;
use App\Command\Resolve\DTO\Response;
use App\Command\Resolve\DTO\SuccessResponse;
use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Common\DTO\Ip;
use App\Common\DTO\Language;
use App\Services\Detector\CountryDetector\CountryDetectorInterface;
use App\Services\Detector\LanguageDetector\LanguageDetectorInterface;
use App\Services\Detector\OsDetector\OsDetectorInterface;

class LanguageCheckHandler extends AbstractCheckHandler
{
    public function __construct(
        private array $allowedLanguages,
        private LanguageDetectorInterface $languageDetector,
    ) {
    }
    public function handle(Command $command): Response
    {
        $userLanguage = $this->languageDetector->detect($command->getAcceptLanguage());
        if ($this->isBlockedLang($userLanguage)) {
            return new BadResponse('Blocked language');
        }

        return $this->nextHandler ? $this->nextHandler->handle($command) : new SuccessResponse();
    }

    private function isBlockedLang(Language $userLanguage): bool
    {
        return !in_array($userLanguage->getValue(), $this->allowedLanguages);
    }
}
