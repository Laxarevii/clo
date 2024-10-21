<?php

namespace App\Action\Factory;

use App\Action\LoadCurlStrategy;
use App\Services\Curl\CurlServiceInterface;
use Illuminate\Foundation\Application;

class LoadCurlStrategyFactory
{
    public function __construct(private Application $app)
    {
    }

    public function create(string $url): LoadCurlStrategy
    {
        return new LoadCurlStrategy(
            $url,
            $this->app->get(CurlServiceInterface::class),
        );
    }
}