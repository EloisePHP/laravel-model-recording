<?php

namespace Eloise\DataAudit\Providers;

use Eloise\DataAudit\Console\Commands\AuditableClassRefreshCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AuditableClassRefreshCommand::class,
            ]);
        }
    }
}
