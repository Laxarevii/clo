<?php

namespace App\Providers;

use App\Command\Resolve\CommandHandler;
use App\Command\Resolve\Factory\CheckHandlerFactory;
use App\Command\Resolve\Handler\CountryCheckHandler;
use App\Command\Resolve\Handler\IspCheckHandler;
use App\Command\Resolve\Handler\LanguageCheckHandler;
use App\Command\Resolve\Handler\OsCheckHandler;
use App\Command\Resolve\Handler\StopWordsRefererCheckHandler;
use App\Command\Resolve\Handler\UriShouldContainCheckHandler;
use App\Command\Resolve\Handler\UriStopWordCheckHandler;
use App\Command\Resolve\Handler\WithOutRefererCheckHandler;
use App\Command\Resolve\Interface\CommandHandlerInterface;
use App\Config\Config;
use App\Services\Checker\UserAgentChecker\UserAgentChecker;
use App\Services\Checker\UserAgentChecker\UserAgentCheckerInterface;
use App\Services\Detector\BlockedIpDetector\BlockedIpDetectorInterface;
use App\Services\Detector\BlockedIpDetector\FileBlockedIpDetector;
use App\Services\Detector\BotDetector\BotDetectorInterface;
use App\Services\Detector\BotDetector\FileBotDetector;
use App\Services\Detector\CountryDetector\CountryDetector;
use App\Services\Detector\CountryDetector\CountryDetectorInterface;
use App\Services\Detector\IspDetector\IspDetector;
use App\Services\Detector\IspDetector\IspDetectorInterface;
use App\Services\Detector\LanguageDetector\LanguageDetector;
use App\Services\Detector\LanguageDetector\LanguageDetectorInterface;
use App\Services\Detector\OsDetector\OsDetector;
use App\Services\Detector\OsDetector\OsDetectorInterface;
use App\Services\Detector\ProxyDetector\Client\BlackboxIpDetectorClient;
use App\Services\Detector\ProxyDetector\Client\ProxyClientInterface;
use App\Services\Detector\ProxyDetector\ProxyDetector;
use App\Services\Detector\ProxyDetector\ProxyDetectorInterface;
use GeoIp2\Database\Reader;
use GuzzleHttp\Client;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Client\ClientInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [Config::class];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(Config::class, function (Application $app) {
            $settings = json_decode(file_get_contents(base_path('config/settings.json')), true);
            return new Config($settings);
        });
        $this->app->bind(LanguageDetectorInterface::class, LanguageDetector::class);
        $this->app->bind(OsDetectorInterface::class, OsDetector::class);
        $this->app->bind(CommandHandlerInterface::class, CommandHandler::class);
        $this->app->bind(CountryDetectorInterface::class, CountryDetector::class);
        $this->app->bind(IspDetectorInterface::class, IspDetector::class);
        $this->app->bind(BotDetectorInterface::class, FileBotDetector::class);
        $this->app->bind(BlockedIpDetectorInterface::class, FileBlockedIpDetector::class);
        $this->app->bind(ProxyDetectorInterface::class, ProxyDetector::class);
        $this->app->bind(UserAgentCheckerInterface::class, UserAgentChecker::class);
        $this->app->bind(ClientInterface::class, Client::class);
        $this->app->bind(ProxyClientInterface::class, BlackboxIpDetectorClient::class);

        $this->app->singleton(BlackboxIpDetectorClient::class, function (Application $app) {
            return new BlackboxIpDetectorClient(
                $app->get(ClientInterface::class)
            );
        });
        $this->app->singleton(UriShouldContainCheckHandler::class, function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            return new UriShouldContainCheckHandler(
                $config->get('tds')['filters']['allowed']['wordsInUri'],
            );
        });
        $this->app->singleton(UriStopWordCheckHandler::class, function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            return new UriStopWordCheckHandler(
                $config->get('tds')['filters']['blocked']['uriWords'],
            );
        });
        $this->app->singleton(StopWordsRefererCheckHandler::class, function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            return new StopWordsRefererCheckHandler(
                $config->get('tds')['filters']['blocked']['referer']['stopwords'],
            );
        });
        $this->app->singleton(LanguageCheckHandler::class, function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            return new LanguageCheckHandler(
                $config->get('tds')['filters']['allowed']['languages'] ?? [],
                $app->get(LanguageDetectorInterface::class)
            );
        });
        $this->app->singleton(WithOutRefererCheckHandler::class, function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            return new WithOutRefererCheckHandler(
                $config->get('tds')['filters']['blocked']['referer']['empty'],
            );
        });
        $this->app->singleton(IspCheckHandler::class, function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            $isps = $config->get('tds')['filters']['blocked']['isps'] ?? [];

            if (!is_array($isps)) {
                throw new \UnexpectedValueException('Blocked ISPs must be an array.');
            }

            return new IspCheckHandler($isps, $app->get(IspDetectorInterface::class));
        });

        $this->app->singleton('IspDetectorService', function (Application $app) {
            return new Reader(
                config_path() . '/locationDetector/asn.mmdb'
            );
        });
        $this->app->singleton('CountryDetectorService', function (Application $app) {
            return new Reader(
                config_path() . '/locationDetector/country.mmdb'
            );
        });
        $this->app->singleton(CountryDetector::class, function (Application $app) {
            return new CountryDetector(
                $app->get('CountryDetectorService')
            );
        });
        $this->app->singleton(IspDetector::class, function (Application $app) {
            return new IspDetector(
                $app->get('IspDetectorService')
            );
        });
        $this->app->singleton(CountryCheckHandler::class, function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            $countries = $config->get('tds')['filters']['allowed']['countries'] ?? [];

            if (!is_array($countries)) {
                throw new \UnexpectedValueException('Allowed countries must be an array.');
            }

            return new CountryCheckHandler($countries, $app->get(CountryDetectorInterface::class));
        });
        $this->app->singleton(OsCheckHandler::class, function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            return new OsCheckHandler(
                $config->get('tds')['filters']['allowed']['os'] ?? [],
                $app->get(OsDetectorInterface::class)
            );
        });
        $this->app->singleton(UserAgentChecker::class, function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            return new UserAgentChecker($config->get('tds')['filters']['blocked']['useragents']);
        });
        $this->app->singleton(FileBlockedIpDetector::class, function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            $path = base_path() . $config->get('tds')['filters']['blocked']['ips']['filePath'];
            return new FileBlockedIpDetector($path);
        });
        $this->app->singleton(FileBotDetector::class, function () {
            $filePath = config('services.detectors.fileBotDetector.filePath');

            if (!is_string($filePath) || empty($filePath)) {
                throw new \InvalidArgumentException('Expected filePath to be a non-empty string');
            }

            return new FileBotDetector($filePath);
        });

        $this->app->singleton(CommandHandler::class, function (Application $app): CommandHandler {
            $configData = config('chainHandlersList.chain_handlers');

            if (!is_array($configData)) {
                throw new \InvalidArgumentException('Expected configuration data to be an array');
            }

            $handlers = array_map(function ($class) use ($app): mixed {
                if (!is_string($class)) {
                    throw new \InvalidArgumentException('Expected class to be a string');
                }
                return $app->make($class);
            }, $configData);

            $checkHandlerFactory = new CheckHandlerFactory($configData);
            $checkHandler = $checkHandlerFactory->create($handlers);

            return new CommandHandler($checkHandler);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
