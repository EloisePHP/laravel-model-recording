<?php

namespace Eloise\DataAudit;

use Eloise\DataAudit\Providers\CommandServiceProvider;
use Eloise\DataAudit\Providers\EventServiceProvider;
use Eloise\DataAudit\Providers\MigrationServiceProvider;
use Eloise\DataAudit\Providers\ObserverServiceProvider;
use Illuminate\Support\ServiceProvider;

class DataAuditServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->app->register(CommandServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(MigrationServiceProvider::class);
    }
}
