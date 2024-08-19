<?php

namespace Eloise\DataAudit\Managers;

use Eloise\DataAudit\Builders\SourceableAuditBuilder;
use Eloise\DataAudit\Constants\AuditableProperties;
use Eloise\DataAudit\Contracts\AuditableModel;
use Eloise\DataAudit\Models\Audit;
use Eloise\DataAudit\Models\AuditAction;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuditModelManager
{
    public function __construct(
        protected AuditableModel $auditableModel,
        protected string $action
    ) {
    }

    public function createAudits(): void
    {
        DB::transaction(function () {
            $this->createDefaultAudit();
            $this->createRelatedAudits();
        });
    }

    public function createRelatedAudits(): void
    {
        $relatedActions = AuditAction::select('name', 'source_class', 'target_class', 'method')
                                        ->where('source_class', get_class($this->auditableModel))
                                        ->where('target_class', '!=', '')
                                        ->get();

        $auditsToInsert = [];

        foreach ($relatedActions as $relatedAction) {
            $data = $this->getResult($this->auditableModel, $relatedAction->name, $relatedAction->method);
            foreach ($data as $value) {
                $targetOptions = [
                    'target_class' => get_class($value),
                    'target_id' => $value->id,
                ];
                $builder = new SourceableAuditBuilder($this->auditableModel, $this->action, $targetOptions);
                $auditsToInsert[] = $builder->toArray();
            }
        }

        // Perform a bulk insert for all audits
        if (!empty($auditsToInsert)) {
            Audit::insert($auditsToInsert);
        }
    }

    public function createDefaultAudit(): void
    {
        $builder = new SourceableAuditBuilder($this->auditableModel, $this->action);
        $auditArray = $builder->toArray();

        Audit::create($auditArray);
    }

    public function getResult(AuditableModel $auditableModel, string $relation, string $method)
    {
        /* These two verifications were done in the moment the audit actions were registered
        if (!method_exists($auditableModel, $method)) {
            throw new \InvalidArgumentException("Method '{$method}' does not exist in " . get_class($auditableModel));
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

        $result = $auditableModel->{$method}();

        // Determine whether the result is a single object or a collection
        $data = $result instanceof \Illuminate\Database\Eloquent\Relations\BelongsTo ||
                $result instanceof \Illuminate\Database\Eloquent\Relations\HasOne ||
                $result instanceof \Illuminate\Database\Eloquent\Relations\MorphOne
                ? [$result->first()] // Returns a single object
                : $result->get();  // Returns a collection of objects

        return $data;
    }
}
