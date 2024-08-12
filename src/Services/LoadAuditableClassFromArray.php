<?php

namespace Eloise\DataAudit\Services;

use Eloise\DataAudit\Constants\Actions;
use Eloise\DataAudit\Models\AuditableClass;
use Eloise\DataAudit\Models\AuditAction;

class LoadAuditableClassFromArray
{

    public function loadAuditableClass(array $auditModel): array
    {
        $auditableClass = AuditableClass::updateOrCreate(
            ['class_name' => $auditModel['class_name']],
            [
                'name' => $auditModel['short_name'],
                'default' => $auditModel['default'],
                'active' => $auditModel['active'],
                'version' => $auditModel['version'],
            ]
        );

        $auditModel['auditable_id'] = (int) $auditableClass->id;

        return $auditModel;
    }

    public function loadDefaultActions(array $auditModel): void
    {
        foreach (Actions::DEFAULT_ACTIONS as $action) {
            AuditAction::updateOrCreate(
                [
                    'name' => $action,
                    'eloise_audit_class_id' => $auditModel['auditable_id'],
                ],
                [
                    'description' => sprintf('%s%s', "Default action for ", Actions::ACTION_CREATED),
                    'version' => $auditModel['version'],
                    'source_class' => $auditModel['source_class'],
                    'target_class' => $auditModel['source_class'],
                ]
            );
        }
    }
}
