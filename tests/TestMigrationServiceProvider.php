<?php

namespace Eloise\DataAudit\Tests;

use Illuminate\Support\ServiceProvider;

class TestMigrationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $testMigrationDirectory = __DIR__ . '/database/migrations';
        $this->publishesMigrations([
            $testMigrationDirectory => database_path('migrations'),
        ]);

        $this->loadMigrationsFrom($testMigrationDirectory);
    }
}
