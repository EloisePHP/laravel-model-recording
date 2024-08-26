<?php

namespace Eloise\RecordModel\Console\Commands;

use Eloise\RecordModel\Constants\Headers;
use Eloise\RecordModel\Contracts\RecordableModel;
use Eloise\RecordModel\Loaders\LoadActions;
use Eloise\RecordModel\Services\RecordableModelsFromProject;
use Eloise\RecordModel\Loaders\LoadRecordableClass;
use Illuminate\Console\Command;

use function Laravel\Prompts\info;
use function Laravel\Prompts\table;

class RecordableClassCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'eloise:record:refresh';

    /**
     * @var string
     */
    protected $description = 'Instatiate all Recordable Classes and Actions to be recordable';

    /**
     * @throws \Exception
     */
    public function handle(
        RecordableModelsFromProject $recordableModelsFromProject
    ): void {
        info('Getting all Recordable Models.');

        $recordableModels = $recordableModelsFromProject->toArray();
        table(
            headers: Headers::RECORDABLE_CLASSES_HEADER,
            rows: $recordableModels
        );
        foreach ($recordableModels as $recordableModel) {
            $load = new LoadRecordableClass();
            $load->load($recordableModel);
        }

        $recordableModels = $recordableModelsFromProject->getRecordableModels();

        /** @var RecordableModel $recordableModel */
        foreach ($recordableModels as $recordableModel) {
             $loader = new LoadActions($recordableModel);
             $loader->load();
        }

        info('All these classes were added to the database.');
    }
}
