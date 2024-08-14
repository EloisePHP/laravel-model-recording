<?php

namespace Eloise\DataAudit\Queries;

use Eloise\DataAudit\Models\Audit;
use Illuminate\Database\Eloquent\Collection;

class AuditQueries 
{
    public const CHUNK_SIZE = 100;

    public function getAuditFromUserAndModelId(
        string $modelName,
        int $modelId = null,
        int $userId = null,
        callable $callback
        ): void
    {
        $query = Audit::query();

        // If both $modelId and $userId are null
        if ($modelId === null && $userId === null) {
            $query->where('source_class', $modelName)
                  ->orWhere('target_class', $modelName);
            }
        // If only $modelId is null
        elseif ($modelId === null) {
            $query->where(function ($q) use ($userId, $modelName) {
                $q->where('user_id', $userId)
                ->where(function ($subQuery) use ($modelName) {
                    $subQuery->where('source_class', $modelName)
                            ->orWhere('target_class', $modelName);
                });
            });
        }
        // If only $userId is null
        elseif ($userId === null) {
            $query->where(function ($q) use ($modelId, $modelName) {
                $q->where(function ($subQuery) use ($modelId, $modelName) {
                    $subQuery->where('source_id', $modelId)
                            ->where('source_class', $modelName);
                })
                ->orWhere(function ($subQuery) use ($modelId, $modelName) {
                    $subQuery->where('target_id', $modelId)
                            ->where('target_class', $modelName);
                });
            });
        }
        // Both $modelId and $userId are not null
        else {
            $query->where(function ($q) use ($modelId, $userId, $modelName) {
                $q->where('user_id', $userId)
                ->where(function ($subQuery) use ($modelId, $modelName) {
                    $subQuery->where('source_id', $modelId)
                            ->where('source_class', $modelName);
                })
                ->orWhere(function ($subQuery) use ($modelId, $modelName, $userId) {
                    $subQuery->where('user_id', $userId)
                            ->where('target_id', $modelId)
                            ->where('target_class', $modelName);
                });
            });
        }

        $query->orderBy('created_at', 'desc');
        // Process the query results in chunks
        $query->chunk(self::CHUNK_SIZE, function ($audits) use ($callback) {
            $callback($audits);
        });
    }
}
