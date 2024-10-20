<?php

namespace App\Command\Resolve\ChainBuilder;

use App\Action\LoadCurlStrategy;
use App\Command\Resolve\Factory\CheckHandlerFactory;
use App\Command\Resolve\Handler\ChainHandler;
use App\Command\Resolve\Handler\CountryCheckHandler;
use App\Command\Resolve\Handler\HandlerAggregator;
use App\Command\Resolve\Handler\HandlerAggregatorObject\HandlerAggregatorObject;
use App\Command\Resolve\Handler\OsCheckHandler;
use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Services\Detector\CountryDetector\CountryDetectorInterface;
use App\Services\Detector\OsDetector\OsDetectorInterface;
use Illuminate\Foundation\Application;

class ChainBuilder
{
    public function __construct(
        private array $config,
        private Application $app,
        private CheckHandlerFactory $checkHandlerFactory,
    ) {
    }

    public function build(): CheckHandlerInterface
    {
        return $this->linkChains([
            $this->prepareDefaultHandlerChain(),
            $this->getBaseHandlerChain(),
        ]);
    }

    private function linkChains(array $handlers): CheckHandlerInterface
    {
        return $this->checkHandlerFactory->create($handlers);
    }

    private function prepareDefaultHandlerChain(): HandlerAggregator
    {
        $configData = $this->config['block']['handlers'];
        $filter = $this->config['block'];
        $app = $this->app;
        $handlers = array_map(function ($class) use ($app): mixed {
            if (!is_string($class)) {
                throw new \InvalidArgumentException('Expected class to be a string');
            }
            return $app->make($class);
        }, $configData);

        $filters[] = new HandlerAggregatorObject(
            $this->linkChains($handlers),
            $this->makeResolver($filter)
        );
        return new HandlerAggregator($filters);
    }

    private function getBaseHandlerChain(): HandlerAggregator
    {
        $filters = [];
        foreach ($this->config['allow']['filters'] as $filter) {
            $innerFilters = [];
            if (isset($filter['geo']['country'])) {
                $innerFilters[] =
                    new CountryCheckHandler($filter['geo']['country'], $this->app->get(CountryDetectorInterface::class));
            }
            if (isset($filter['os'])) {
                $innerFilters[] =
                    new OsCheckHandler($filter['os'], $this->app->get(OsDetectorInterface::class));
            }
            $innerFilters = $this->linkChains($innerFilters);
            $filters[] = new HandlerAggregatorObject(
                $innerFilters,
                $this->makeResolver($filter)
            );
        }
        return new HandlerAggregator($filters);
    }

    private function makeResolver(array $filter): LoadCurlStrategy
    {
        return match ($filter['action']) {
            'curl' => new LoadCurlStrategy($filter['url']),
            default => throw new \InvalidArgumentException('Invalid action')
        };
    }
}
