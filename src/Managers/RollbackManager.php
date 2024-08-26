<?php

namespace Eloise\RecordModel\Managers;

use Eloise\RecordModel\Constants\RecordableProperties;
use Eloise\RecordModel\Contracts\RecordableModel;
use Eloise\RecordModel\Queries\RollbackQuery;
use Illuminate\Support\Carbon;

class RollbackManager
{
    public function __construct(
        protected RecordableModel $recordableModel,
        protected Carbon|null $dateTime,
    ) {
    }

    public function retrieve(): RecordableModel
    {
        $newModel = $this->recordableModel->replicate();

        $oldModel = $this->retrieveDiff();
        if ($oldModel === null) {
            return $newModel;
        }

        foreach ($oldModel as $key => $value) {
            if ($key == $this->recordableModel->getKeyName()) {
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
        $records = $query->getCollectionOfDiffs($this->recordableModel, $this->dateTime);

        if ($records->isEmpty()) {
            return null;
        }

        /** @var array<string, mixed> $rollback */
        $rollback = [];

        foreach ($records as $diffs) {
            foreach ($diffs as $diff) {
                $rollback[$diff[RecordableProperties::FIELD]]
                    = $diff[RecordableProperties::ORIGINAL_VALUE]
                    ?? $diff[RecordableProperties::NEW_VALUE];
            }

            if ($records->count() === 1) {
                return $rollback;
            }
        }

        return $rollback;
    }
}
