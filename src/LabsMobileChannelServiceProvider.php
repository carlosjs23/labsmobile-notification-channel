<?php

namespace Illuminate\Notifications;

use Illuminate\Notifications\Channels\LabsMobileSmsChannel;
use Illuminate\Notifications\Client\Client;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

class LabsMobileChannelServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/labsmobile.php', 'labsmobile');

        $this->app->singleton(Client::class, function ($app) {
            $config = $app['config']['labsmobile'];

            $httpClient = null;

            if ($httpClient = $config['http_client'] ?? null) {
                $httpClient = $app->make($httpClient);
            } elseif (! class_exists('GuzzleHttp\Client')) {
                throw new RuntimeException(
                    'The LabsMobile client requires a "psr/http-client-implementation" class such as Guzzle.'
                );
            }

            return LabsMobile::make($app['config']['labsmobile'], $httpClient)->client();
        });

        $this->app->bind(LabsMobileSmsChannel::class, function ($app) {
            return new LabsMobileSmsChannel(
                $app->make(Client::class),
                $app['config']['labsmobile.sms_from']
            );
        });

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('labsmobile', function ($app) {
                return $app->make(LabsMobileSmsChannel::class);
            });
        });
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/labsmobile.php' => $this->app->configPath('labsmobile.php'),
            ], 'labsmobile');
        }
    }
}
