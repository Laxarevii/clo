<?php

namespace App\Services\Detector\CountryDetector;

use App\Common\DTO\Country;
use App\Common\DTO\Ip;
use App\Exceptions\NoIsoCodeCountryException;
use GeoIp2\Exception\AddressNotFoundException;
use GeoIp2\ProviderInterface;

class CountryDetector implements CountryDetectorInterface
{
    public function __construct(private ProviderInterface $countryDetector)
    {
    }

    /**
     * @throws \App\Exceptions\NoIsoCodeCountryException
     */
    public function detect(Ip $ip): Country
    {
        try {
            $country = $this->countryDetector->country($ip->getValue());
            $isoCode = $country->country->isoCode;
            if (null === $isoCode) {
                throw new NoIsoCodeCountryException('No iso for ip ' . $ip->getValue());
            }
            return new Country($isoCode);
        } catch (AddressNotFoundException $exception) {
            throw new \InvalidArgumentException("Country for ip '{$ip->getValue()}' does not exist.");
        }
    }
}
