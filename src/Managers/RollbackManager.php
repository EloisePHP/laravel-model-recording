<?php

namespace Eloise\DataAudit\Managers;

use Eloise\DataAudit\Constants\AuditableProperties;
use Eloise\DataAudit\Contracts\AuditableModel;
use Eloise\DataAudit\Queries\RollbackQuery;
use Illuminate\Support\Carbon;

class RollbackManager
{
    public function __construct(
        protected AuditableModel $auditableModel,
        protected Carbon|null $dateTime,
    ) {
    }

    public function retrieve(): AuditableModel
    {
        $newModel = $this->auditableModel->replicate();

        $oldModel = $this->retrieveDiff();
        if ($oldModel === null) {
            return $newModel;
        }

        foreach ($oldModel as $key => $value) {
            if ($key == $this->auditableModel->getKeyName()) {
                continue;
            }
            $newModel->setAttribute($key, $value);
        }

        return $newModel;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function retrieveDiff(): array | null
    {
        $query = new RollbackQuery();
        $audits = $query->getCollectionOfDiffs($this->auditableModel, $this->dateTime);

        if ($audits->isEmpty()) {
            return null;
        }

        /** @var array<string, mixed> $rollback */
        $rollback = [];

        foreach ($audits as $diffs) {
            foreach ($diffs as $diff) {
                $rollback[$diff[AuditableProperties::FIELD]]
                    = $diff[AuditableProperties::ORIGINAL_VALUE]
                    ?? $diff[AuditableProperties::NEW_VALUE];
            }

            if ($audits->count() === 1) {
                return $rollback;
            }
        }

        return $rollback;
    }
}
