<?php

namespace Eloise\DataAudit\Providers;

use Eloise\DataAudit\Contracts\AuditableModel;
use Eloise\DataAudit\Managers\RollbackManager as RewindManager;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class RollbackManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton('rollback.manager.factory', function () {
            return new class {
                protected $auditableModel;
                protected $dateTime;

                public function forModel(AuditableModel $model)
                {
                    $this->auditableModel = $model;
                    return $this;
                }

                public function atDate(Carbon $dateTime = null)
                {
                    $this->dateTime = $dateTime;
                    return $this;
                }

                public function retrieve()
                {
                    return (new RewindManager($this->auditableModel, $this->dateTime))->retrieve();
                }
            };
        });
    }
}
