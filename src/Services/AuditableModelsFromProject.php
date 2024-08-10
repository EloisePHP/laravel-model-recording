<?php

namespace Eloise\DataAudit\Services;

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
    public function getAuditableModels(): ?array
    {
        $auditableModels = [];
        $files = File::allFiles(app_path('Models'));

        foreach ($files as $file) {
            $namespace = PathNames::PROJECT_MODELS;
            $className = $namespace . pathinfo($file->getFilename(), PATHINFO_FILENAME);

            if (!class_exists($className)) {
                $errorMessage = sprintf('Class %s does not exist.', $className);
                throw new Exception($errorMessage);
            }

            $reflectionClass = new ReflectionClass($className);

            if (!$reflectionClass->implementsInterface(PathNames::AUDITABLE_CONTRACT)) {
                continue;
            }

            $modelClass = new $className();

            $auditableModels[] = [
                'class_name' => $className,
                'short_name' => $reflectionClass->getShortName(),
                'default' => $modelClass->defaultAudit(),
                'active' => $modelClass->activeAudit(),
                'version' => $modelClass->versionAudit(),
                'source_class' => $modelClass->getSourceModelClass(),
            ];


        }

        return $auditableModels;
    }
}
