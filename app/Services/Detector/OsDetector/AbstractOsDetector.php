<?php

namespace App\Services\Detector\OsDetector;

use App\Common\DTO\UserAgent;
use App\Common\DTO\Os;
use App\Exceptions\UnknownOSException;

abstract class AbstractOsDetector implements OsDetectorInterface
{
    protected ?OsDetectorInterface $nextDetector = null;

    public function setNext(OsDetectorInterface $detector): OsDetectorInterface
    {
        $this->nextDetector = $detector;
        return $detector;
    }

    /**
     * @throws \App\Exceptions\UnknownOSException
     */
    public function detect(UserAgent $userAgent): Os
    {
        $os = $this->doDetect($userAgent);
        if ($os !== null) {
            return $os;
        }

        if ($this->nextDetector !== null) {
            return $this->nextDetector->detect($userAgent);
        }

        throw new UnknownOSException();
    }

    abstract protected function doDetect(UserAgent $userAgent): ?Os;
}
