<?php

namespace Eloise\DataAudit\Suppliers;

use Eloise\DataAudit\Constants\Actions;
use Eloise\DataAudit\Contracts\AuditableModel;
use Eloise\DataAudit\Models\AuditableClass;
use Eloise\DataAudit\Models\AuditAction;

class AuditableClassSupplier
{
    public function getAuditableClassFromModel(AuditableModel $model)
    {
        $className = get_class($model);
        return AuditableClass::where([
            'class_name' => $className
        ])->first();
        $auditAction = AuditAction::where([
            'eloise_audit_class_id' => $auditClass->id,
            'name' => Actions::ACTION_CREATED,
        ])->first();
    }
}
