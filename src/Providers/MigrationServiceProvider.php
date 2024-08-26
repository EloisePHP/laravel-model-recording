<?php

namespace Eloise\RecordModel\Providers;

use Illuminate\Support\ServiceProvider;

class MigrationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $migrationDirectory = __DIR__ . '/../../database/migrations';
        $this->publishesMigrations([
            $migrationDirectory => database_path('migrations'),
        ]);

        $this->loadMigrationsFrom($migrationDirectory);
    }
}
