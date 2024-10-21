<?php

namespace App\Providers;

use App\Action\Factory\LoadCurlStrategyFactory;
use App\Action\LoadCurlStrategy;
use App\Action\LoadLocalPageStrategy;
use App\Action\RedirectStrategy;
use App\Command\Resolve\ChainBuilder\ChainBuilder;
use App\Command\Resolve\CommandHandler;
use App\Command\Resolve\Factory\CheckHandlerFactory;
use App\Command\Resolve\Factory\HandlersFactory;
use App\Command\Resolve\Factory\HandlerWrapChainFactory;
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
use App\Services\Curl\CurlService;
use App\Services\Curl\CurlServiceInterface;
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
use App\Services\Detector\OsDetector\Factory\OsDetectorFactory;
use App\Services\Detector\OsDetector\OsDetector;
use App\Services\Detector\OsDetector\OsDetectorInterface;
use App\Services\Detector\ProxyDetector\Client\BlackboxIpDetectorClient;
use App\Services\Detector\ProxyDetector\Client\ProxyClientInterface;
use App\Services\Detector\ProxyDetector\ProxyDetector;
use App\Services\Detector\ProxyDetector\ProxyDetectorInterface;
use App\Services\Resolver\ActionResolverInterface\AllowActionResolver\AllowActionResolver;
use App\Services\Resolver\ActionResolverInterface\AllowActionResolver\AllowActionResolverFactory;
use App\Services\Resolver\ActionResolverInterface\BlockActionResolver\BlockActionResolver;
use App\Services\Resolver\ActionResolverInterface\BlockActionResolver\BlockActionResolverFactory;
use App\Services\Resolver\CloakResolver\CloakResolver;
use App\Services\Resolver\CloakResolver\CloakResolverInterface;
use GeoIp2\Database\Reader;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use RuntimeException;
use UnexpectedValueException;

/**
 *
 */
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
        $this->app->singleton(Config::class, function () {
            $json = file_get_contents(base_path('config/settings.json'));

            if ($json === false) {
                throw new RuntimeException('Failed to read the settings file');
            }

            $settings = json_decode($json, true);

            if (!is_array($settings)) {
                throw new RuntimeException('Settings file must return a valid JSON array');
            }

            return new Config($settings);
        });

        $this->app->bind(CurlServiceInterface::class, CurlService::class);
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
        $this->app->bind(CloakResolverInterface::class, CloakResolver::class);

        $this->app->singleton(CloakResolver::class, function (Application $app) {
            return new CloakResolver(
                $app->get(BlockActionResolver::class),
                $app->get(AllowActionResolver::class),
            );
        });
        $this->app->singleton(BlockActionResolverFactory::class, function (Application $app) {
            return new BlockActionResolverFactory(
                $app->get(ContainerInterface::class)
            );
        });
        $this->app->singleton('AllowRedirectStrategy', function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            $urls = $config->get('black')['landing']['redirect']['urls'];
            $key = array_rand($urls);
            $status = $config->get('black')['landing']['redirect']['status'];
            return new RedirectStrategy($urls[$key], $status);
        });
        $this->app->singleton('BlockRedirectStrategy', function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            $urls = $config->get('white')['redirect']['urls'];
            $status = $config->get('white')['redirect']['status'];
            $key = array_rand($urls);
            return new RedirectStrategy($urls[$key], $status);
        });
        $this->app->singleton('AllowCurlStrategy', function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            $urls = $config->get('black')['landing']['curl']['urls'];
            $key = array_rand($urls);
            return new LoadCurlStrategy($urls[$key], $app->get(CurlServiceInterface::class));
        });
        $this->app->singleton('BlockLoadCurlStrategy', function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            $urls = $config->get('white')['curl']['urls'];
            $key = array_rand($urls);
            return new LoadCurlStrategy($urls[$key], $app->get(CurlServiceInterface::class));
        });
        $this->app->singleton('BlockLoadLocalPageStrategy', function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            $url = $config->get('white')['localPage']['url'];
            return new LoadLocalPageStrategy($url);
        });
        $this->app->singleton('AllowLoadLocalPageStrategy', function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            $url = $config->get('black')['landing']['localPage']['url'];
            return new LoadLocalPageStrategy($url);
        });

        $this->app->singleton(ChainBuilder::class, function (Application $app) {
            $config = config('settings.cloak');
            return new ChainBuilder(
                $config,
                $app->get(HandlerWrapChainFactory::class),
                $app->get(HandlersFactory::class),
                $app->get(LoadCurlStrategyFactory::class),
                $app->get(CheckHandlerFactory::class),
                $app->get(OsDetectorInterface::class),
                $app->get(CountryDetectorInterface::class),
            );
        });
        $this->app->singleton(AllowActionResolver::class, function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            /** @var AllowActionResolverFactory $factory */
            $factory = $app->make(AllowActionResolverFactory::class);

            $action =
                $config->get('black')['landing']['action'] ?? throw new InvalidArgumentException('Invalid action');

            return new AllowActionResolver(
                $factory->create($action)
            );
        });

        $this->app->singleton(BlockActionResolver::class, function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            /** @var BlockActionResolverFactory $factory */
            $factory = $app->make(BlockActionResolverFactory::class);

            $action = $config->get('white')['action'] ?? throw new InvalidArgumentException('Invalid action');

            return new BlockActionResolver(
                $factory->create($action)
            );
        });
        $this->app->singleton(BlackboxIpDetectorClient::class, function (Application $app) {
            /** @var GuzzleClientInterface $client */
            $client = $app->get(ClientInterface::class);
            return new BlackboxIpDetectorClient(
                $client
            );
        });

        $this->app->singleton(UriShouldContainCheckHandler::class, function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);

            // Get the 'tds' configuration
            $tdsConfig = $config->get('tds', []);

            // Ensure that 'tds' config is an array
            if (!is_array($tdsConfig)) {
                throw new InvalidArgumentException('Expected tds configuration to be an array');
            }

            // Access the filters and ensure it is an array
            $filters = $tdsConfig['filters'] ?? [];
            if (!is_array($filters)) {
                throw new InvalidArgumentException('Expected filters configuration to be an array');
            }

            // Access the allowed words
            $allowed = $filters['allowed'] ?? [];
            if (!is_array($allowed)) {
                throw new InvalidArgumentException('Expected allowed filters configuration to be an array');
            }

            // Get the wordsInUri and ensure it's an array
            $words = $allowed['wordsInUri'] ?? [];
            if (!is_array($words)) {
                throw new InvalidArgumentException('Expected wordsInUri to be an array');
            }

            return new UriShouldContainCheckHandler($words);
        });


        $this->app->singleton(UriStopWordCheckHandler::class, function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);

            /** @var array<string> $uriWords */
            $uriWords = $config->get('tds')['filters']['blocked']['uriWords'] ?? [];

            return new UriStopWordCheckHandler($uriWords);
        });

        $this->app->singleton(StopWordsRefererCheckHandler::class, function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            /** @var array<string> $stopWords */
            $stopWords = $config->get('tds')['filters']['blocked']['referer']['stopWords'] ?? [];

            return new StopWordsRefererCheckHandler(
                $stopWords
            );
        });

        $this->app->singleton(LanguageCheckHandler::class, function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            /** @var LanguageDetectorInterface $languageDetector */
            $languageDetector = $app->get(LanguageDetectorInterface::class);

            /** @var array<string> $languages */
            $languages = $config->get('tds')['filters']['allowed']['languages'] ?? [];

            return new LanguageCheckHandler($languages, $languageDetector);
        });

        $this->app->singleton(WithOutRefererCheckHandler::class, function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            return new WithOutRefererCheckHandler(
                (bool)$config->get('tds', false)['filters']['blocked']['referer']['empty'],
            );
        });

        $this->app->singleton(IspCheckHandler::class, function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            $isps = $config->get('tds')['filters']['blocked']['isps'] ?? [];

            if (!is_array($isps)) {
                throw new UnexpectedValueException('Blocked ISPs must be an array.');
            }

            /** @var IspDetectorInterface $service */
            $service = $app->get(IspDetectorInterface::class);

            return new IspCheckHandler($isps, $service);
        });

        $this->app->singleton('IspDetectorService', function () {
            return new Reader(
                config_path() . '/locationDetector/asn.mmdb'
            );
        });
        $this->app->singleton('CountryDetectorService', function () {
            return new Reader(
                config_path() . '/locationDetector/country.mmdb'
            );
        });
        $this->app->singleton(CountryDetector::class, function (Application $app) {
            /** @var Reader $service */
            $service = $app->get('CountryDetectorService');
            return new CountryDetector(
                $service
            );
        });
        $this->app->singleton(IspDetector::class, function (Application $app) {
            /** @var Reader $service */
            $service = $app->get('IspDetectorService');
            return new IspDetector(
                $service
            );
        });
        $this->app->singleton(CountryCheckHandler::class, function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            $countries = $config->get('tds')['filters']['allowed']['countries'] ?? [];

            if (!is_array($countries)) {
                throw new UnexpectedValueException('Allowed countries must be an array.');
            }
            /** @var CountryDetectorInterface $countryDetector */
            $countryDetector = $app->get(CountryDetectorInterface::class);
            return new CountryCheckHandler($countries, $countryDetector);
        });

        $this->app->singleton(OsCheckHandler::class, function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);
            /** @var OsDetectorInterface $osDetector */
            $osDetector = $app->get(OsDetectorInterface::class);

            $osFilters = $config->get('tds')['filters']['allowed']['os'] ?? [];

            if (!is_array($osFilters)) {
                throw new InvalidArgumentException('Expected OS filters to be an array');
            }

            return new OsCheckHandler($osFilters, $osDetector);
        });

        $this->app->singleton(UserAgentChecker::class, function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);

            /** @var array<string> $userAgents */
            $userAgents = $config->get('tds')['filters']['blocked']['userAgents'] ?? [];

            return new UserAgentChecker($userAgents);
        });


        $this->app->singleton(FileBlockedIpDetector::class, function (Application $app) {
            /** @var Config $config */
            $config = $app->get(Config::class);

            /** @var string $filePath */
            $filePath = $config->get('tds')['filters']['blocked']['ips']['filePath'] ?? '';

            if (empty($filePath)) {
                throw new InvalidArgumentException('Expected filePath to be a non-empty string');
            }

            $path = base_path() . '/' . $filePath;

            return new FileBlockedIpDetector($path);
        });


        $this->app->singleton(FileBotDetector::class, function () {
            $filePath = config('services.detectors.fileBotDetector.filePath');

            if (!is_string($filePath) || empty($filePath)) {
                throw new InvalidArgumentException('Expected filePath to be a non-empty string');
            }

            return new FileBotDetector($filePath);
        });

        $this->app->singleton(CommandHandler::class, function (Application $app): CommandHandler {
            /** @var ChainBuilder $builder */
            $builder = $app->get(ChainBuilder::class);

            return new CommandHandler(
                $builder->build(),
            );
        });

        $this->app->singleton(OsDetector::class, function (Application $app): OsDetector {
            $configData = config('chainOsDetectorsList.chainDetectors');

            if (!is_array($configData)) {
                throw new InvalidArgumentException('Expected configuration data to be an array');
            }

            $detectors = array_map(function ($class) use ($app): mixed {
                if (!is_string($class)) {
                    throw new InvalidArgumentException('Expected class to be a string');
                }
                return $app->make($class);
            }, $configData);

            $detectorsFactory = new OsDetectorFactory();
            $detector = $detectorsFactory->create($detectors);

            return new OsDetector($detector);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DB::listen(function ($query) {
            Log::info(
                $query->sql,
                $query->bindings,
                $query->time
            );
        });
    }
}
