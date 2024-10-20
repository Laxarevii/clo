<?php

namespace App\Services\Detector\CountryDetector;

use App\Entity\Country;
use App\Entity\Ip;

interface CountryDetectorInterface
{
    public function detect(Ip $ip): Country;
}
