<?php

namespace Eloise\RecordModel\Providers;

use Eloise\RecordModel\Contracts\RecordableModel;
use Eloise\RecordModel\Managers\RecordModelManager as RecordManager;
use Illuminate\Support\ServiceProvider;

class RecordManagerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton('record.manager.factory', function () {
            return new class {
                protected RecordableModel $recordableModel;
                protected string $action;
                protected string | null $message = null;

                public function forModel(RecordableModel $model): self
                {
                    $this->recordableModel = $model;

                    return $this;
                }

                public function atAction(string $action): self
                {
                    $this->action = $action;

                    return $this;
                }

                public function withMessage(string $message): self
                {
                    $this->message = $message;

                    return $this;
                }

                public function create(): void
                {
                    (new RecordManager($this->recordableModel, $this->action))->createRecords();
                }
            };
        });
    }
}
