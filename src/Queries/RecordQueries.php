<?php

namespace Eloise\RecordModel\Queries;

use Eloise\RecordModel\Constants\Queries;
use Eloise\RecordModel\Models\Record;

class RecordQueries
{
    public function getRecordFromUserAndModelId(
        string $modelName,
        int $modelId = null,
        int $userId = null,
        callable $callback
    ): void {
        $query = Record::query();

        $baseCondition = function ($q) use ($modelId, $modelName) {
            if ($modelId !== null) {
                $q->where('source_id', $modelId)->where('source_class', $modelName)
                  ->orWhere(function ($subQuery) use ($modelId, $modelName) {
                      $subQuery->where('target_id', $modelId)->where('target_class', $modelName);
                  });
            } else {
                $q->where('source_class', $modelName)
                  ->orWhere('target_class', $modelName);
            }
        };

        // Adding user condition
        if ($userId !== null) {
            $query->where(function ($q) use ($baseCondition, $userId) {
                $q->where('user_id', $userId)->where($baseCondition);
            });
        } else {
            $query->where($baseCondition);
        }

        $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
        $query->chunk(Queries::CHUNK_SIZE, function ($records) use ($callback) {
            $shouldContinue = $callback($records);

            if ($shouldContinue === false) {
                return false;
            }
        });
    }
}
