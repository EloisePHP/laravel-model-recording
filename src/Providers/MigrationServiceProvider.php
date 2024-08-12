<?php

namespace Eloise\DataAudit\Providers;

use Illuminate\Support\ServiceProvider;

class MigrationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register bindings or anything else necessary
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $migrationDirectory = __DIR__ . '/../../database/migrations';
        $this->publishesMigrations([
            $migrationDirectory => database_path('migrations'),
        ]);

        $this->loadMigrationsFrom($migrationDirectory);
    }
}
