<?php

namespace App\Command\Resolve\Factory;

use Illuminate\Foundation\Application;
use InvalidArgumentException;

class HandlersFactory
{
    public function __construct(
        private Application $application
    ) {
    }

    public function create(array $handlers): array
    {
        $app = $this->application;
        return array_map(function ($class) use ($app): mixed {
            if (!is_string($class)) {
                throw new InvalidArgumentException('Expected class to be a string');
            }
            return $app->make($class);
        }, $handlers);
    }
}