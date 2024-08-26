<?php

namespace Eloise\RecordModel\Services;

use Eloise\RecordModel\Builders\ArrayFromRecordableContractBuilder;
use Eloise\RecordModel\Contracts\RecordableModel as RecordableModelContract;
use Eloise\RecordModel\Constants\PathNames;
use Exception;
use Illuminate\Support\Facades\File;

class RecordableModelsFromProject
{
    public function __construct(
        protected RecordableModelContractChecker $checker
    ) {
    }

    /**
    * This method gets all models implementing the RecordableModel contract.
    *
    * @return array<int,array{
    *     class_name: string,
    *     short_name: string,
    *     version: string,
    *     source_class: string
    * }>
    * @throws Exception
    */
    public function getRecordableModels(): array
    {
        $recordableModels = [];
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

            /** @var RecordableModelContract $modelClass */
            $modelClass = new $className();
            $recordableModels[] = $modelClass;
        }

        return $recordableModels;
    }

    /**
     * @return array<int,array{
     *     class_name: string,
     *     short_name: string,
     *     version: string,
     *     source_class: string
     * }>
     */
    public function toArray(): array
    {
        $recordableModels = $this->getRecordableModels();

        $arrayOfRecordableModels = [];
        /** @var RecordableModelContract $recordableModel */
        foreach ($recordableModels as $recordableModel) {
            $builder = new ArrayFromRecordableContractBuilder($recordableModel);
            $arrayOfRecordableModels[] = $builder->toArray();
        }

        return $arrayOfRecordableModels;
    }
}
