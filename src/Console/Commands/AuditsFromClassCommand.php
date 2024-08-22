<?php

namespace Eloise\DataAudit\Console\Commands;

use Eloise\DataAudit\Services\AuditableModelsFromProject;
use Eloise\DataAudit\Suppliers\AuditsCommandSupplier;
use Eloise\DataAudit\Loaders\LoadAuditableClass;
use Eloise\DataAudit\Queries\AuditQueries;
use Illuminate\Console\Command;

use function Laravel\Prompts\info;

class AuditsFromClassCommand extends Command
{
    protected AuditsCommandSupplier $supplier;

    public function __construct(
        protected AuditQueries $queries,
    ) {
        $this->supplier = new AuditsCommandSupplier($queries);
        parent::__construct();
    }

    /**
     * @var string
     */
    protected $signature = 'eloise:audit:model:audits {modelName?} {--modelId=} {--userId=}';

    /**
     * @var string
     */
    protected $description = 'Get audits from Model Name, Model Id and User Id';

    /**
     * @throws \Exception
     */
    public function handle(
        AuditableModelsFromProject $auditableModelsFromProject
    ): void {
        if ($this->argument('modelName') === null) {
            info('You must provide a model Name as an argument');
            info($this->signature);
            return;
        }

        /** @var string $modelName */
        $modelName = $this->argument('modelName');

        $auditableModels = $auditableModelsFromProject->toArray();

        $modelClassName = '';
        $modelFound = false;
        foreach ($auditableModels as $auditableModel) {
            if ($auditableModel['short_name'] === $modelName) {
                /** @var string $modelClassName */
                $modelClassName = $auditableModel['class_name'];
                $modelFound = true;
            }
            $load = new LoadAuditableClass();
            $load->load($auditableModel);
        }
        if (!$modelFound) {
            info($modelName . ' has not been found in the Auditable Models');
            info('Check eloise:audit:class command to see all auditable Models');
            return;
        }
        /** @var int|null $modelId */
        $modelId = $this->option('modelId');
        /** @var int|null $userId */
        $userId = $this->option('userId');

        $dataFound = false;
        $dataFound = $this->supplier->getAuditsFromParameter($modelClassName, $modelId, $userId);

        $message = $dataFound ? 'No more Audits found' : 'No data found with the given parameters.';
        info($message);
    }
}
