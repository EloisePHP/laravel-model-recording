<?php

namespace Eloise\RecordModel\Managers;

use Eloise\RecordModel\Builders\SourceableRecordBuilder;
use Eloise\RecordModel\Contracts\RecordableModel;
use Eloise\RecordModel\Models\RecordAction;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\DB;

class RecordModelManager
{
    public function __construct(
        protected RecordableModel $recordableModel,
        protected string $action,
        protected string | null $message = null,
    ) {
    }

    public function createRecords(): void
    {
        DB::transaction(function () {
            $this->createDefaultRecord();
            //$this->createRelatedRecords();
        });
    }

    public function createDefaultRecord(): void
    {
        $builder = new SourceableRecordBuilder($this->recordableModel, $this->action, message: $this->message);
        $record = $builder->toRecord();
        $record->save();
    }

    public function createRelatedRecords(): void
    {
        $relatedActions = RecordAction::select('name', 'source_class', 'target_class', 'method')
                                        ->where('source_class', get_class($this->recordableModel))
                                        ->where('target_class', '!=', '')
                                        ->get();

        $recordsToInsert = [];

        foreach ($relatedActions as $relatedAction) {
            $data = $this->getResult($this->recordableModel, $relatedAction->name, $relatedAction->method);
            foreach ($data as $value) {
                $targetOptions = [
                    'target_class' => get_class($value),
                    'target_id' => $value->id,
                ];
                $builder = new SourceableRecordBuilder($this->recordableModel, $this->action, $targetOptions);
                $recordsToInsert[] = $builder->toRecord();
            }
        }

        if (!empty($recordsToInsert)) {
            foreach ($recordsToInsert as $record) {
                $record->save();
            }
            //Record::insert($recordsToInsert);
        }
    }

    public function getResult(RecordableModel $recordableModel, string $relation, string $method)
    {
        /* These two verifications were done in the moment the record actions were registered
        if (!method_exists($recordableModel, $method)) {
            throw new \InvalidArgumentException("Method '{$method}' does not exist in " . get_class($recordableModel));
        }

        if (!$result instanceof Relation) {
            throw new \InvalidArgumentException("The method '{$method}' does not return a valid relationship.");
            }

        if (class_basename($result) !== $relation) {
            throw new \InvalidArgumentException(
                    "Expected a '{$relation}' relationship, but got '" . class_basename($result) . "'."
                );
        }
        */

        $result = $recordableModel->{$method}();

        // Determine whether the result is a single object or a collection
        $data = $result instanceof BelongsTo ||
                $result instanceof HasOne ||
                $result instanceof MorphOne
                ? [$result->first()] // Returns a single object
                : $result->get();  // Returns a collection of objects

        return $data;
    }
}
