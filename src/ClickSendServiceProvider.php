<?php

namespace NotificationChannels\ClickSend;

use Illuminate\Support\ServiceProvider;

class ClickSendServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(ClickSendApi::class, function () {

            $config = config('services.clicksend');

            return new ClickSendApi($config['username'], $config['api_key'], $config['sms_from']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [ClickSendApi::class];
    }
}
