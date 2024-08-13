<?php

namespace Eloise\DataAudit\Services;

use Eloise\DataAudit\Builder\ArrayFromAuditableContractBuilder;
use Eloise\DataAudit\Constants\PathNames;
use Exception;
use Illuminate\Support\Facades\File;
use ReflectionClass;

class AuditableModelsFromProject
{
    /**
     * This method gets all models implementing AuditableModel Contract
     *
     * @throws Exception
     */
    public function getAuditableModels(): array
    {
        $auditableModels = [];
        $paths = new PathNames();
        $namespace = $paths->getProjectModelsPath();

        $files = File::allFiles(app_path($namespace));
        foreach ($files as $file) {
            $prefixClassName = $paths->getPrefixClassName();
            $className =  $prefixClassName . pathinfo($file->getFilename(), PATHINFO_FILENAME);

            if (!class_exists($className)) {
                $errorMessage = sprintf('Class %s does not exist.', $className);
                throw new Exception($errorMessage);
            }

            $reflectionClass = new ReflectionClass($className);

            if (!$reflectionClass->implementsInterface(PathNames::AUDITABLE_CONTRACT)) {
                continue;
            }

            $modelClass = new $className();

            $builder = new ArrayFromAuditableContractBuilder($modelClass);
            $auditableModels[] = $builder->toArray();
        }

        return $auditableModels;
    }
}
