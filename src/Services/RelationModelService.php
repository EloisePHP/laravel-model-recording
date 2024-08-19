<?php

namespace Eloise\DataAudit\Services;

use Eloise\DataAudit\Contracts\AuditableModel;
use ReflectionClass;
use Illuminate\Database\Eloquent\Relations\Relation;

class RelationModelService
{
    public function getAllRelatedModels(AuditableModel $auditableModel): array
    {
        $relatedModels = [];
        $reflection = new ReflectionClass($auditableModel);

        foreach ($reflection->getMethods() as $method) {
            // Check if the method belongs to the auditable model class
            if (
                $method->class === get_class($auditableModel)
                && $method->isPublic()
                && $method->getNumberOfParameters() === 0
            ) {
                try {
                    $result = $method->invoke($auditableModel);

                    if ($result instanceof Relation) {
                        $relationType = class_basename($result);
                            $relatedModelClass = $result->getRelated();

                            $relatedModels[] = [
                                'method' => $method->getName(),
                                'relation' => $relationType,
                                'related_model' => get_class($relatedModelClass),
                            ];
                    }
                } catch (\Throwable) {
                    continue;
                }
            }
        }

        return $relatedModels;

        // Flatten and filter out null values
        return array_filter(array_merge(...array_map(function ($item) {
            return is_array($item) ? $item : [$item];
        }, $relatedModels)));
    }
}
