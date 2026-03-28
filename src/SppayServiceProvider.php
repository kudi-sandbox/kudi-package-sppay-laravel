<?php

namespace Mboateng\Sppay;

use Illuminate\Support\ServiceProvider;
use Mboateng\Sppay\Contracts\SppayClientContract;

class SppayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sppay.php', 'sppay');

        $this->app->singleton(SppayClientContract::class, function ($app) {
            $config = $app['config']->get('sppay', []);

            return new SppayClient(
                (string) ($config['base_url'] ?? 'https://engine.sppay.dev'),
                $config['access_token'] ?? null,
                (float) ($config['timeout'] ?? 30),
                (float) ($config['connect_timeout'] ?? 10),
            );
        });

        $this->app->singleton(SppayClient::class, fn ($app) => $app->make(SppayClientContract::class));

        $this->app->alias(SppayClientContract::class, 'sppay');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/sppay.php' => $this->app->configPath('sppay.php'),
            ], 'sppay-config');
        }
    }
}
