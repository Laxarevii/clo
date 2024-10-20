<?php

namespace App\Command\Resolve\ChainBuilder;

use App\Command\Resolve\Handler\ChainHandler;
use App\Command\Resolve\Handler\CountryCheckHandler;
use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Services\Detector\CountryDetector\CountryDetectorInterface;
use Illuminate\Foundation\Application;

class ChainBuilder
{
    public function __construct(private array $config, private Application $app)
    {
    }

    public function build(): ChainHandler
    {
        $filters = [];
        foreach ($this->config['allow']['filters'] as $filter) {
            if (isset($filter['geo']['country'])) {
                $filters[] =
                    new CountryCheckHandler($filter['geo']['country'], $this->app->get(CountryDetectorInterface::class));
            }
        }
        return new ChainHandler($filters);
    }
}
