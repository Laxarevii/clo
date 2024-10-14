<?php

namespace App\Services\Detector\CountryDetector;

use App\Common\DTO\Country;
use App\Common\DTO\Ip;

interface CountryDetectorInterface
{
    public function detect(Ip $ip): Country;
}
