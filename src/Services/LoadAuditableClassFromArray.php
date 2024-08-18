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

    public function loadActions(array $auditModel, string $action = null): void
    {
        $actionsArray = $auditModel['default'] ? Actions::DEFAULT_ACTIONS : [];
        if ($action !== null && !in_array($action, $actionsArray)) {
            $actionsArray[] = $action;
        }

        foreach ($actionsArray as $action) {
            $this->updateOrCreateAction($auditModel, $action);
        }
    }

    public function updateOrCreateAction(array $auditModel, string $action)
    {
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
