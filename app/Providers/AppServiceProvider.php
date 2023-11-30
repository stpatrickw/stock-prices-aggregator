<?php

namespace App\Providers;

use App\Services\AlphaVantage\AlphaVantageAutoMapperConfig;
use App\Services\AlphaVantage\AlphaVantageService;
use App\Services\StockMarketSourceInterface;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\AutoMapperInterface;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Client\ClientInterface;

class AppServiceProvider extends ServiceProvider
{

    public array $bindings = [
        StockMarketSourceInterface::class => AlphaVantageService::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(AutoMapperInterface::class, function () {
            $config = new AutoMapperConfig();
            (new AlphaVantageAutoMapperConfig)->configure($config);

            return new AutoMapper($config);
        });

        $this->app->bind(ClientInterface::class, function(Application $app) {
            return Http::buildClient();
        });

        $this->app->bind(AlphaVantageService::class, function(Application $app) {
            $apiKey = config('app.alpha_vantage_api_key') ?? '';
            $client = Http::withOptions([
                'base_uri' => 'https://www.alphavantage.co/query',
            ]);

            return new AlphaVantageService(
                $app->make(AutoMapperInterface::class),
                $client,
                $apiKey
            );
        });

    }
}
