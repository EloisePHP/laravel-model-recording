<?php

namespace Eloise\DataAudit\Managers;

use Eloise\DataAudit\Constants\AuditableProperties;
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

        /** @phpstan-ignore-next-line */
        $audit->source_id = $auditableModel->id;

        $audit->action = $action;
        $audit->version = $auditableModel->versionAudit();

        $audit->changes = $this->getChangesInAuditableModel($auditableModel);

        $currentUser = Auth::user();

        if ($currentUser instanceof User) {
            $audit->user_id = $currentUser->id;
        }
        $audit->save();

        return $audit;
    }

    /**
     * Get the changes in the auditable model.
     *
     * @param AuditableModel $auditableModel
     * @return array<int, array<string, array<string, mixed>>>
     */
    public function getChangesInAuditableModel(AuditableModel $auditableModel): array
    {
        $auditableModelChanges = $auditableModel->getDirty();

        $changes = [];
        foreach ($auditableModelChanges as $attribute => $newValue) {
            $originalValue = $auditableModel->getOriginal($attribute);
            $changes[] = [
                $attribute => [
                    AuditableProperties::ORIGINAL_VALUE => $originalValue,
                    AuditableProperties::NEW_VALUE => $newValue,
                ],
            ];
        }

        return $changes;
    }
}
