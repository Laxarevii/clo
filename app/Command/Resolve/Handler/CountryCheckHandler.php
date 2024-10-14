<?php

namespace App\Command\Resolve\Handler;

use App\Command\Resolve\Command;
use App\Command\Resolve\DTO\BadResponse;
use App\Command\Resolve\DTO\Response;
use App\Command\Resolve\DTO\SuccessResponse;
use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Common\DTO\Ip;
use App\Services\Detector\CountryDetector\CountryDetectorInterface;
use App\Services\Detector\OsDetector\OsDetectorInterface;

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
