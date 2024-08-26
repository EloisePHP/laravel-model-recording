<?php

namespace Eloise\RecordModel\Services;

use Eloise\RecordModel\Constants\PathNames;
use ReflectionClass;

class RecordableModelContractChecker
{
    public function check(string $className): ReflectionClass|false
    {
        $reflectionClass = new ReflectionClass($className);

        if (!$reflectionClass->implementsInterface(PathNames::RECORDABLE_CONTRACT)) {
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
