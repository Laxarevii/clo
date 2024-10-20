<?php

namespace App\Command\Resolve\Handler;

use App\Command\Common\DTO\BadResponse;
use App\Command\Common\DTO\Response;
use App\Command\Common\DTO\SuccessResponse;
use App\Command\Resolve\Command;
use App\Services\Detector\CountryDetector\CountryDetectorInterface;

class CountryCheckHandler extends AbstractCheckHandler
{
    public function __construct(
        private array $allowedCountryCodes,
        private CountryDetectorInterface $countryDetector,
    ) {
    }

    public function handle(Command $command): Response
    {
        $userCountry = $this->countryDetector->detect($command->getIp());
        if (!in_array($userCountry->getIsoCode(), $this->allowedCountryCodes, true)) {
            return new BadResponse('Blocked Country');
        }
        return $this->nextHandler ? $this->nextHandler->handle($command) : new SuccessResponse();
    }
}
