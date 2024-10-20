<?php

namespace App\Services\Detector\IspDetector;

use App\Entity\Ip;
use App\Entity\Isp;
use App\Exceptions\UnknownIspException;
use Exception;
use GeoIp2\Database\Reader;
use InvalidArgumentException;

class IspDetector implements IspDetectorInterface
{
    public function __construct(private Reader $ispDetector)
    {
    }

    public function detect(Ip $ip): Isp
    {
        try {
            //TODO refactor http://ip-api.com/json/89.209.96.158
            $reader = $this->ispDetector->asn($ip->getValue());
            if (null === $reader->autonomousSystemOrganization) {
                throw new UnknownIspException();
            }
            return new Isp($reader->autonomousSystemOrganization);
        } catch (Exception $exception) {
            throw new InvalidArgumentException("Isp for ip '{$ip->getValue()}' does not exist.");
        }
    }
}
