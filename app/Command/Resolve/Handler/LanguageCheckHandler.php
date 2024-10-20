<?php

namespace App\Command\Resolve\Handler;

use App\Command\Common\DTO\BadResponse;
use App\Command\Common\DTO\Response;
use App\Command\Common\DTO\SuccessResponse;
use App\Command\Resolve\Command;
use App\Entity\Language;
use App\Services\Detector\LanguageDetector\LanguageDetectorInterface;

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
