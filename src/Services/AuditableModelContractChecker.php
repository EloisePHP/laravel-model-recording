<?php

namespace Eloise\DataAudit\Services;

use Eloise\DataAudit\Constants\PathNames;
use ReflectionClass;

class AuditableModelContractChecker
{
    public function check(string $className): ReflectionClass|false
    {
        $reflectionClass = new ReflectionClass($className);

        if (!$reflectionClass->implementsInterface(PathNames::AUDITABLE_CONTRACT)) {
            return false;
        }

        return $reflectionClass;
    }

    public function arrayCheckerFilter(array $arrayOfModels): array
    {
        $filteredModels = [];

        foreach ($arrayOfModels as $modelArray) {
            $modelChecked = $this->check($modelArray['related_model']);
            if ($modelChecked) {
                $filteredModels[] = $modelArray;
            }
        }

        return $filteredModels;
    }
}
