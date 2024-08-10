<?php

namespace Eloise\DataAudit\Services;

use Eloise\DataAudit\Constants\Actions;
use Eloise\DataAudit\Models\AuditableClass;
use Eloise\DataAudit\Models\AuditAction;

class LoadAuditableClassFromArray
{
    public function __construct(
        protected array $auditModel,
    ) {
    }

    public function loadAuditableClass(): void
    {
        $auditableClass = AuditableClass::updateOrCreate(
            ['class_name' => $this->auditModel['class_name']],
            [
                'name' => $this->auditModel['short_name'],
                'default' => $this->auditModel['default'],
                'active' => $this->auditModel['active'],
                'version' => $this->auditModel['version'],
            ]
        );

        $this->auditModel['auditable_id'] = (int) $auditableClass->id;
    }

    public function loadDefaultActions(): void
    {
        foreach (Actions::DEFAULT_ACTIONS as $action) {
            AuditAction::updateOrCreate(
                [
                    'name' => $action,
                    'eloise_audit_class_id' => $this->auditModel['auditable_id'],
                ],
                [
                    'description' => sprintf('%s %s', "Default action for ", Actions::ACTION_CREATED),
                    'version' => $this->auditModel['version'],
                    'source_class' => $this->auditModel['source_class'],
                    'target_class' => $this->auditModel['source_class'],
                ]
            );
        }
    }
}
