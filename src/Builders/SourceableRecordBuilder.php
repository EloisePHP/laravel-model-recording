<?php

namespace Eloise\RecordModel\Builders;

use Eloise\RecordModel\Constants\RecordableProperties;
use Eloise\RecordModel\Contracts\RecordableModel;
use Eloise\RecordModel\Models\Record;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User;

class SourceableRecordBuilder
{
    /**
     * @param array<string, mixed> $targetOptions
     */
    public function __construct(
        protected RecordableModel $recordableModel,
        protected string $action,
        protected array $targetOptions = [],
        protected string | null $message = null,
    ) {
    }

    public function toRecord(): Record
    {
        $record = new Record();

        $record->action = $this->action;

        $sourceClass = $this->recordableModel->getSourceModelClass();
        $record->source_class = $sourceClass;
        /** @phpstan-ignore-next-line */
        $record->source_id = $this->recordableModel->id;

        $record->target_class = $this->targetOptions['target_class'] ?? null;
        $record->target_id = $this->targetOptions['target_id'] ?? null;

        $record->version = $this->recordableModel->versionRecord();
        $record->diff = $this->getChangesInRecordableModel();
        $record->message = $this->message;
        $currentUser = Auth::user();

        if ($currentUser instanceof User) {
            $record->user_id = $currentUser->id;
        }

        return $record;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $sourceClass = $this->recordableModel->getSourceModelClass();

        $recordArray = [
            'action' => $this->action,
            'source_class' => $sourceClass,
            /** @phpstan-ignore-next-line */
            'source_id' => $this->recordableModel->id,
            'target_class' => $this->targetOptions['target_class'] ?? null,
            'target_id' => $this->targetOptions['target_id'] ?? null,
            'version' => $this->recordableModel->versionRecord(),
            'diff' => $this->getChangesInRecordableModel(),
            'user_id' => optional(Auth::user())->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        return $recordArray;
    }

    /**
     * Get the diff in the recordable model.
     *
     * @return array<int, array<string, array<string, mixed>>>
     */
    public function getChangesInRecordableModel(): array
    {
        $recordableModelChanges = $this->recordableModel->getDirty();

        $diff = [];
        foreach ($recordableModelChanges as $attribute => $newValue) {
            $originalValue = $this->recordableModel->getOriginal($attribute);
            $diff[] = [
                RecordableProperties::FIELD => $attribute,
                RecordableProperties::ORIGINAL_VALUE => $originalValue,
                RecordableProperties::NEW_VALUE => $newValue,
            ];
        }

        return $diff;
    }
}
