<?php

namespace Eloise\RecordModel\Console\Commands;

use Eloise\RecordModel\Services\RecordableModelsFromProject;
use Eloise\RecordModel\Suppliers\RecordsCommandSupplier;
use Eloise\RecordModel\Loaders\LoadRecordableClass;
use Eloise\RecordModel\Queries\RecordQueries;
use Illuminate\Console\Command;

use function Laravel\Prompts\info;

class RecordsFromClassCommand extends Command
{
    protected RecordsCommandSupplier $supplier;

    public function __construct(
        protected RecordQueries $queries,
    ) {
        $this->supplier = new RecordsCommandSupplier($queries);
        parent::__construct();
    }

    /**
     * @var string
     */
    protected $signature = 'eloise:record:model:records {modelName?} {--modelId=} {--userId=}';

    /**
     * @var string
     */
    protected $description = 'Get records from Model Name, Model Id and User Id';

    /**
     * @throws \Exception
     */
    public function handle(
        RecordableModelsFromProject $recordableModelsFromProject
    ): void {
        if ($this->argument('modelName') === null) {
            info('You must provide a model Name as an argument');
            info($this->signature);
            return;
        }

        /** @var string $modelName */
        $modelName = $this->argument('modelName');

        $recordableModels = $recordableModelsFromProject->toArray();

        $modelClassName = '';
        $modelFound = false;
        foreach ($recordableModels as $recordableModel) {
            if ($recordableModel['short_name'] === $modelName) {
                /** @var string $modelClassName */
                $modelClassName = $recordableModel['class_name'];
                $modelFound = true;
            }
            $load = new LoadRecordableClass();
            $load->load($recordableModel);
        }
        if (!$modelFound) {
            info($modelName . ' has not been found in the Recordable Models');
            info('Check eloise:record:class command to see all recordable Models');
            return;
        }
        /** @var int|null $modelId */
        $modelId = $this->option('modelId');
        /** @var int|null $userId */
        $userId = $this->option('userId');

        $dataFound = false;
        $dataFound = $this->supplier->getRecordsFromParameter($modelClassName, $modelId, $userId);

        $message = $dataFound ? 'No more Records found' : 'No data found with the given parameters.';
        info($message);
    }
}
