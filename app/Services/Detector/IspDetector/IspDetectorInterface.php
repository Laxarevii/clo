<?php

namespace App\Services\Detector\IspDetector;

use App\Common\DTO\Ip;
use App\Common\DTO\Isp;

interface IspDetectorInterface
{
    public function detect(Ip $ip): Isp;
}
