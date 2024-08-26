<?php

namespace Eloise\RecordModel\Providers;

use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/paths.php' => config_path('paths.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/paths.php',
            'paths'
        );
    }
}
