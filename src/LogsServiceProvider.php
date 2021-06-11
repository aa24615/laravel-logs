<?php

namespace Zyan\LaravelLogs;

use Illuminate\Support\ServiceProvider;

class LogsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            \dirname(__DIR__) . '/config/logs.php' => config_path('logs.php'),
        ], 'logs-config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            \dirname(__DIR__) . '/config/logs.php',
            'logs'
        );
    }
}
