<?php

namespace App\Command\Resolve\ChainBuilder;

use App\Action\ActionInterface;
use App\Action\Factory\LoadCurlStrategyFactory;
use App\Action\RedirectStrategy;
use App\Command\Resolve\Factory\CheckHandlerFactory;
use App\Command\Resolve\Factory\HandlersFactory;
use App\Command\Resolve\Factory\HandlerWrapChainFactory;
use App\Command\Resolve\Handler\CountryCheckHandler;
use App\Command\Resolve\Handler\OsCheckHandler;
use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Command\Resolve\Wrapper\HandlerActionWrap;
use App\Command\Resolve\Wrapper\HandlerAggregatorWrapChain;
use App\Command\Resolve\Wrapper\HandlerWrapChain;
use App\Command\Resolve\Wrapper\HandlerWrapChainInterface;
use App\Services\Detector\CountryDetector\CountryDetectorInterface;
use App\Services\Detector\OsDetector\OsDetectorInterface;
use InvalidArgumentException;

class ChainBuilder
{
    public function __construct(
        private array $config,
        private HandlerWrapChainFactory $handlerWrapChainFactory,
        private HandlersFactory $handlersFactory,
        private LoadCurlStrategyFactory $loadCurlStrategyFactory,
        private CheckHandlerFactory $checkHandlerFactory,
        private OsDetectorInterface $osDetector,
        private CountryDetectorInterface $countryDetector,
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
        return $this->handlerWrapChainFactory->create($handlers);
    }

    private function prepareDefaultHandlerChain(): HandlerWrapChainInterface
    {
        $configData = $this->config['block']['handlers'];
        $filter = $this->config['block'];
        $handlers = $this->handlersFactory->create($configData);
        $handlersChain = $this->linkHandlerChains($handlers);

        return new HandlerWrapChain(
            $handlersChain,
            $this->makeResolver($filter),
        );
    }

    private function linkHandlerChains(array $handlers): CheckHandlerInterface
    {
        return $this->checkHandlerFactory->create($handlers);
    }

    private function makeResolver(array $filter): ActionInterface
    {
        //FIXME
        return match ($filter['action']) {
            ActionInterface::CURL => $this->loadCurlStrategyFactory->create($filter['url']),
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
                        $this->countryDetector
                    );
            }
            if (isset($filter['os'])) {
                $innerFilters[] =
                    new OsCheckHandler($filter['os'], $this->osDetector);
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
}
