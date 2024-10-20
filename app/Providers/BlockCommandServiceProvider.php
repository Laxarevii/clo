<?php

namespace App\Providers;

use App\Command\Resolve\Block\CommandHandler;
use App\Command\Resolve\Block\Factory\CheckBlockHandlerFactory;
use App\Command\Resolve\Block\Factory\CommandHandlerFactory;
use App\Command\Resolve\Block\Interface\CommandHandlerInterface;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class BlockCommandServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CommandHandlerInterface::class, CommandHandler::class);

        $this->app->singleton(CommandHandlerFactory::class, function (Application $app): CommandHandlerFactory {
            $handlers = array_map(function ($class) use ($app) {
                /** @var Application $app */
                return $app->get($class);
            },
                config('settings.cloak.block.handlers')
            );
            $checkBlockHandlerFactory = $app->get(CheckBlockHandlerFactory::class);
            return new CommandHandlerFactory($checkBlockHandlerFactory->create($handlers));
        });
        $this->app->singleton(CommandHandler::class, function (Application $app): CommandHandler {
            /** @var CommandHandlerFactory $factory */
            $factory = $app->get(CommandHandlerFactory::class);
            return $factory->create();
        });
    }
}
