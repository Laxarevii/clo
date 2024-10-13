<?php

namespace App\Services\Detector\IspDetector;

use App\Common\DTO\Ip;
use App\Common\DTO\Isp;
use GeoIp2\ProviderInterface;

class IspDetector implements IspDetectorInterface
{
    public function __construct(private ProviderInterface $ispDetector)
    {
    }

    public function detect(Ip $ip): Isp
    {
        try {
            //TODO refactor http://ip-api.com/json/89.209.96.158
            $reader = $this->ispDetector->asn($ip->getValue());
            return new Isp($reader->autonomousSystemOrganization);
        } catch (\Exception $exception) {
            throw new \InvalidArgumentException("Isp for ip '{$ip->getValue()}' does not exist.");
        }
    }
}
