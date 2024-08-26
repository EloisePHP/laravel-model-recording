<?php

namespace Eloise\RecordModel;

use Eloise\RecordModel\Providers\CommandServiceProvider;
use Eloise\RecordModel\Providers\ConfigServiceProvider;
use Eloise\RecordModel\Providers\EventServiceProvider;
use Eloise\RecordModel\Providers\MigrationServiceProvider;
use Eloise\RecordModel\Providers\RecordManagerServiceProvider;
use Eloise\RecordModel\Providers\RollbackManagerServiceProvider;
use Illuminate\Support\ServiceProvider;

class RecordModelServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->app->register(CommandServiceProvider::class);
        $this->app->register(ConfigServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(MigrationServiceProvider::class);
        $this->app->register(RecordManagerServiceProvider::class);
        $this->app->register(RollbackManagerServiceProvider::class);
    }
}
