<?php

namespace Eloise\DataAudit\Suppliers;

use Eloise\DataAudit\Constants\Actions;
use Eloise\DataAudit\Contracts\AuditableModel;
use Eloise\DataAudit\Models\AuditableClass;
use Eloise\DataAudit\Models\AuditAction;

class AuditableSupplier
{
    public function getActionClassFromModel(AuditableModel $model, string $actionName): AuditAction
    {
        $className = get_class($model);
        $auditClass = AuditableClass::where([
            'class_name' => $className
        ])->first();

        return AuditAction::where([
            'eloise_audit_class_id' => $auditClass->id,
            'name' => $actionName,
        ])->first();
    }
}
