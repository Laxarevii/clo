<?php

namespace App\Services\Detector\OsDetector\Factory;

use App\Services\Detector\OsDetector\OsDetectorInterface;
use InvalidArgumentException;

class OsDetectorFactory
{
    /**
     * @param array $detectors
     * @return \App\Services\Detector\OsDetector\OsDetectorInterface
     */
    public function create(array $detectors): OsDetectorInterface
    {
        if (empty($detectors)) {
            throw new InvalidArgumentException('No handlers configured.');
        }

        $firstDetector = array_shift($detectors);
        $currentDetector = $firstDetector;

        foreach ($detectors as $detector) {
            $currentDetector->setNext($detector);
            $currentDetector = $detector;
        }

        return $firstDetector;
    }
}
