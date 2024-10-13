<?php

namespace App\Services\Detector\CountryDetector;

use App\Common\DTO\Country;
use App\Common\DTO\Ip;
use GeoIp2\Exception\AddressNotFoundException;
use GeoIp2\ProviderInterface;

class CountryDetector implements CountryDetectorInterface
{
    public function __construct(private ProviderInterface $countryDetector)
    {
    }

    public function detect(Ip $ip): Country
    {
        try {
            $country = $this->countryDetector->country($ip->getValue());
            $isoCode = $country->country->isoCode;
            return new Country($isoCode);
        } catch (AddressNotFoundException $exception) {
            throw new \InvalidArgumentException("Country for ip '{$ip->getValue()}' does not exist.");
        }
    }
}
