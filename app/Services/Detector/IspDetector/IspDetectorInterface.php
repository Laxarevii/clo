<?php

namespace App\Services\Detector\IspDetector;

use App\Entity\Ip;
use App\Entity\Isp;

interface IspDetectorInterface
{
    public function detect(Ip $ip): Isp;
}
