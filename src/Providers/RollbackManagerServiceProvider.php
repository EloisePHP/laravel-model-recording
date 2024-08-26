<?php

namespace Eloise\RecordModel\Providers;

use Eloise\RecordModel\Contracts\RecordableModel;
use Eloise\RecordModel\Managers\RollbackManager as RewindManager;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class RollbackManagerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton('rollback.manager.factory', function () {
            return new class {
                protected RecordableModel $recordableModel;
                protected Carbon | null $dateTime;

                public function forModel(RecordableModel $model): self
                {
                    $this->recordableModel = $model;

                    return $this;
                }

                public function atDate(Carbon $dateTime = null): self
                {
                    $this->dateTime = $dateTime;

                    return $this;
                }

                public function retrieve(): mixed
                {
                    return (new RewindManager($this->recordableModel, $this->dateTime))->retrieve();
                }
            };
        });
    }
}
