<?php

namespace Eloise\DataAudit\Providers;

use Eloise\DataAudit\Console\Commands\AuditableClassRefreshCommand;
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
