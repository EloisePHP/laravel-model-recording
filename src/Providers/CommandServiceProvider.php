<?php

namespace Eloise\RecordModel\Providers;

use Eloise\RecordModel\Console\Commands\RecordableClassCommand;
use Eloise\RecordModel\Console\Commands\RecordsFromClassCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RecordsFromClassCommand::class,
                RecordableClassCommand::class,
            ]);
        }
    }
}
