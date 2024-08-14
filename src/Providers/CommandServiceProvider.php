<?php

namespace Eloise\DataAudit\Providers;

use Eloise\DataAudit\Console\Commands\AuditableClassCommand;
use Eloise\DataAudit\Console\Commands\AuditsFromClassCommand;
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
                AuditsFromClassCommand::class,
                AuditableClassCommand::class,
            ]);
        }
    }
}
