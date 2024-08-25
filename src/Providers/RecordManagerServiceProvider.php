<?php

namespace Eloise\DataAudit\Providers;

use Eloise\DataAudit\Contracts\AuditableModel;
use Eloise\DataAudit\Managers\AuditModelManager as RecordManager;
use Illuminate\Support\ServiceProvider;

class RecordManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton('record.manager.factory', function () {
            return new class {
                protected $auditableModel;
                protected $action;
                protected $message = null;

                public function forModel(AuditableModel $model)
                {
                    $this->auditableModel = $model;
                    return $this;
                }

                public function atAction(string $action)
                {
                    $this->action = $action;
                    return $this;
                }

                public function withMessage($message)
                {
                    $this->message = $message;
                }

                public function create()
                {
                    return (new RecordManager($this->auditableModel, $this->action))->createAudits();
                }
            };
        });
    }
}
