<?php

namespace Eloise\DataAudit\Services;

use Eloise\DataAudit\Constants\Actions;
use Eloise\DataAudit\Models\AuditableClass;
use Eloise\DataAudit\Models\AuditAction;

class LoadAuditableClassFromArray
{
    /**
     * Load or create an auditable class from an array.
     *
     * @param array{
     *     class_name: string,
     *     short_name: string,
     *     default: bool,
     *     active: bool,
     *     version: string,
     *     auditable_id?: int
     * } $auditModel
     * @return array{
     *     class_name: string,
     *     short_name: string,
     *     default: bool,
     *     active: bool,
     *     version: string,
     *     auditable_id: int
     * }
     */
    public function loadAuditableClass(array $auditModel): array
    {
        $auditableClass = AuditableClass::updateOrCreate(
            ['class_name' => $auditModel['class_name']],
            [
                'name' => $auditModel['short_name'],
                'version' => $auditModel['version'],
            ]
        );

        $auditModel['auditable_id'] = (int) $auditableClass->id;

        return $auditModel;
    }

    /**
     * Load actions for the auditable model.
     *
     * @param array{
     *     class_name: string,
     *     short_name: string,
     *     default: bool,
     *     active: bool,
     *     version: string,
     *     auditable_id: int
     * } $auditModel
     * @param string|null $action
     * @return void
     */
    public function loadActions(array $auditModel, string $action = null): void
    {
        // Ensure the 'source_class' key is added before passing it to updateOrCreateAction
        $auditModel['source_class'] = $auditModel['class_name'];

        $actionsArray = $auditModel['default'] ? Actions::DEFAULT_ACTIONS : [];
        if ($action !== null && !in_array($action, $actionsArray)) {
            $actionsArray[] = $action;
        }

        foreach ($actionsArray as $action) {
            $this->updateOrCreateAction($auditModel, $action);
        }
    }

   /**
     * Update or create an audit action.
     *
     * @param array{
     *     class_name: string,
     *     short_name: string,
     *     default: bool,
     *     active: bool,
     *     version: string,
     *     auditable_id: int,
     *     source_class: string
     * } $auditModel
     * @param string $action
     * @return void
     */
    public function updateOrCreateAction(array $auditModel, string $action): void
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
