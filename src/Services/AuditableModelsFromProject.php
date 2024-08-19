<?php

namespace Eloise\DataAudit\Services;

use Eloise\DataAudit\Builders\ArrayFromAuditableContractBuilder;
use Eloise\DataAudit\Contracts\AuditableModel as AuditableModelContract;
use Eloise\DataAudit\Constants\PathNames;
use Exception;
use Illuminate\Support\Facades\File;

class AuditableModelsFromProject
{
    public function __construct(
        protected AuditableModelContractChecker $checker
    ) {
    }

    /**
    * This method gets all models implementing the AuditableModel contract.
    *
    * @return array<int,array{
    *     class_name: string,
    *     short_name: string,
    *     version: string,
    *     source_class: string
    * }>
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

            if (!$this->checker->check($className)) {
                continue;
            }

            /** @var AuditableModelContract $modelClass */
            $modelClass = new $className();
            $auditableModels[] = $modelClass;
        }

        return $auditableModels;
    }

    public function toArray(): array
    {
        $auditableModels = $this->getAuditableModels();

        $arrayOfAuditableModels = [];
        /** @var AuditableModelContract $auditableModel */
        foreach ($auditableModels as $auditableModel) {
            $builder = new ArrayFromAuditableContractBuilder($auditableModel);
            $arrayOfAuditableModels[] = $builder->toArray();
        }

        return $arrayOfAuditableModels;
    }
}
