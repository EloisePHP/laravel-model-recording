<?php

namespace Eloise\DataAudit\Managers;

use Eloise\DataAudit\Contracts\AuditableModel;
use Eloise\DataAudit\Models\Audit;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

class AuditModelManager
{

    public function createAudit(AuditableModel $auditableModel, string $action): Audit
    {
        
        $audit = new Audit();

        $sourceClass = $auditableModel->getSourceModelClass();
        $audit->source_class = $sourceClass;
        $audit->source_id = $auditableModel->id;
        $audit->action = $action;
        $audit->version = $auditableModel->versionAudit();

        $currentUser = Auth::user();

        $audit->user_id = null;
        if ($currentUser instanceof User) {
            $audit->user_id = $currentUser->id;
        }
        $audit->save();

        return $audit;
    }

}
