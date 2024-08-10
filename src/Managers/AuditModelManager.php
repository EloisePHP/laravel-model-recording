<?php

namespace Eloise\DataAudit\Managers;

use Eloise\DataAudit\Contracts\AuditableModel;
use Eloise\DataAudit\Models\Audit;
use Eloise\DataAudit\Models\AuditAction;
use Eloise\DataAudit\Suppliers\AuditableSupplier;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

class AuditModelManager
{
    public function __construct(
        protected AuditableSupplier $supplier
    ) {}

    public function createAudit(AuditableModel $auditableModel, string $action): Audit
    {
        $auditAction = $this->supplier->getActionClassFromModel($auditableModel, $action);

        $audit = new Audit();

        $sourceClass = $auditableModel->getSourceModelClass();
        $audit->source_class = $sourceClass;
        $audit->source_id = $auditableModel->id;
        $audit->action = $auditAction->name;
        $audit->version = $auditAction->version;

        $audit->setAuditAction()->associate($auditAction);

        $currentUser = Auth::user();
        $currentUserId = 1;
        if ($currentUser instanceof User) {
            $currentUserId = $currentUser->id;
        }
        $audit->user_id = $currentUserId;
        $audit->save();

        return $audit;
    }

}
