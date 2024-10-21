<?php

namespace App\Command\Resolve\ChainBuilder;

use App\Action\ActionInterface;
use App\Action\LoadCurlStrategy;
use App\Action\RedirectStrategy;
use App\Command\Resolve\Factory\CheckHandlerFactory;
use App\Command\Resolve\Factory\HandlerWrapChainFactory;
use App\Command\Resolve\Handler\CountryCheckHandler;
use App\Command\Resolve\Handler\OsCheckHandler;
use App\Command\Resolve\Wrapper\HandlerActionWrap;
use App\Command\Resolve\Wrapper\HandlerAggregatorWrapChain;
use App\Command\Resolve\Wrapper\HandlerWrapChain;
use App\Command\Resolve\Wrapper\HandlerWrapChainInterface;
use App\Services\Detector\CountryDetector\CountryDetectorInterface;
use App\Services\Detector\OsDetector\OsDetectorInterface;
use Illuminate\Foundation\Application;
use InvalidArgumentException;

class ChainBuilder
{
    public function __construct(
        private array $config,
        private Application $app,
    ) {
    }

    public function build(): HandlerWrapChainInterface
    {
        return $this->linkChains([
            $this->prepareDefaultHandlerChain(),
            $this->getBaseHandlerChain(),
        ]);
    }

    private function linkChains(array $handlers): HandlerWrapChainInterface
    {
        return ($this->app->get(HandlerWrapChainFactory::class))->create($handlers);
    }

    private function prepareDefaultHandlerChain(): HandlerWrapChainInterface
    {
        $configData = $this->config['block']['handlers'];
        $filter = $this->config['block'];
        $app = $this->app;
        $handlers = array_map(function ($class) use ($app): mixed {
            if (!is_string($class)) {
                throw new InvalidArgumentException('Expected class to be a string');
            }
            return $app->make($class);
        }, $configData);

        $handlersChain = $this->linkHandlerChains($handlers);

        return new HandlerWrapChain(
            $handlersChain,
            $this->makeResolver($filter),
        );
    }

    private function makeResolver(array $filter): ActionInterface
    {
        return match ($filter['action']) {
            ActionInterface::CURL => new LoadCurlStrategy($filter['url']),
            ActionInterface::REDIRECT => new RedirectStrategy($filter['url']),
            default => throw new InvalidArgumentException('Invalid action')
        };
    }

    private function getBaseHandlerChain(): HandlerWrapChainInterface
    {
        $filters = [];
        foreach ($this->config['allow']['filters'] as $filter) {
            $innerFilters = [];
            if (isset($filter['geo']['country'])) {
                $innerFilters[] =
                    new CountryCheckHandler(
                        $filter['geo']['country'],
                        $this->app->get(CountryDetectorInterface::class)
                    );
            }
            if (isset($filter['os'])) {
                $innerFilters[] =
                    new OsCheckHandler($filter['os'], $this->app->get(OsDetectorInterface::class));
            }
            $filters[] = new HandlerActionWrap(
                $this->linkHandlerChains($innerFilters),
                $this->makeResolver($filter),
            );
        }
        $filter = $this->config['block'];
        return new HandlerAggregatorWrapChain(
            $filters,
            $this->makeResolver($filter),
        );
    }

    private function linkHandlerChains(array $handlers)
    {
        return ($this->app->get(CheckHandlerFactory::class))->create($handlers);
    }
}
