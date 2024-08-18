<?php

namespace Eloise\DataAudit\Tests;

use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    public const string MODELS_FOLDER_TEST = '/../../../../../tests/Fixtures/Models';
    public const string PREFIX_CLASS_NAME_TEST = 'Eloise\\DataAudit\\Tests\\Fixtures\\Models\\';

    protected function getPackageProviders($app)
    {
        return [
            \Eloise\DataAudit\DataAuditServiceProvider::class,
            \Eloise\DataAudit\Tests\TestMigrationServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('paths', require __DIR__ . '/../config/paths.php');

        // Set up the SQLite in-memory database
        $app['config']->set('database.default', 'testing');
        $app['config']->set(
            'database.connections.testing',
            [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]
        );
        $app['config']->set('queue.default', 'sync');
    }

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('paths.models_folder', self::MODELS_FOLDER_TEST);
        Config::set('paths.prefix_class_name', self::PREFIX_CLASS_NAME_TEST);

        // Migrating tables from packages
        $this->artisan('migrate', [
            '--database' => 'testing',
            '--realpath' => realpath(__DIR__ . '/../database/migrations'),
        ]);

        $this->app['config']->set('cache.default', 'array');
    }
}
