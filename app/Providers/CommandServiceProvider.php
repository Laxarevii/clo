<?php

namespace App\Providers;

use App\Command\Resolve\CommandHandler;
use App\Command\Resolve\Factory\CommandHandlerFactory;
use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Config\Config;
use App\Services\Detector\IspDetector\IspDetector;
use App\Services\Detector\IspDetector\IspDetectorInterface;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IspDetectorInterface::class, IspDetector::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
