<?php

namespace Eloise\RecordModel\Services;

use Eloise\RecordModel\Contracts\RecordableModel;
use ReflectionClass;
use Illuminate\Database\Eloquent\Relations\Relation;

class RelationModelService
{
    /**
     * @return array<int, array<string, string>>
     */
    public function getAllRelatedModels(RecordableModel $recordableModel): array
    {
        $relatedModels = [];
        $reflection = new ReflectionClass($recordableModel);

        foreach ($reflection->getMethods() as $method) {
            if (
                $method->class === get_class($recordableModel)
                && $method->isPublic()
                && $method->getNumberOfParameters() === 0
            ) {
                try {
                    $result = $method->invoke($recordableModel);

                    if ($result instanceof Relation) {
                        $relationType = class_basename($result);
                        $relatedModelClass = get_class($result->getRelated());

                        $relatedModels[] = [
                            'method' => $method->getName(),
                            'relation' => $relationType,
                            'related_model' => $relatedModelClass,
                        ];
                    }
                } catch (\Throwable) {
                    continue;
                }
            }
        }

        return $relatedModels;
    }
}
