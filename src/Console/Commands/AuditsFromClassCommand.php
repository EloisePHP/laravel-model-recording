<?php

namespace Eloise\DataAudit\Console\Commands;

use Eloise\DataAudit\Constants\Headers;
use Eloise\DataAudit\Constants\Queries;
use Eloise\DataAudit\Queries\AuditQueries;
use Eloise\DataAudit\Services\AuditableModelsFromProject;
use Eloise\DataAudit\Services\LoadAuditableClassFromArray;
use Illuminate\Console\Command;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\table;

class AuditsFromClassCommand extends Command
{
    public function __construct(
        protected AuditQueries $auditQueries,
    ) {
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
            $load = new LoadAuditableClassFromArray();
            $load->loadAuditableClass($auditableModel);
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

        $this->getAuditsFromParameter($modelClassName, $modelId, $userId);
    }

    public function getAuditsFromParameter(string $modelClassName, int|null $modelId, int|null $userId): void
    {
        $dataFound = false;
        $this->auditQueries->getAuditFromUserAndModelId(
            $modelClassName,
            $modelId,
            $userId,
            function ($audits) use (&$rows, &$dataFound) {
                $rows = [];
                foreach ($audits as $audit) {
                    $rows[] = $audit->toArrayForTable();
                }
                table(
                    headers: Headers::AUDIT_HEADERS,
                    rows: $rows
                );

                if (!$dataFound) {
                    $dataFound = true;
                }

                if (count($rows) < Queries::CHUNK_SIZE) {
                    return false;
                }

                if (!confirm('Do you want to load more data?', true)) {
                    return false;
                }
                return true;
            }
        );

        $message = $dataFound ? 'No more Audits found' : 'No data found with the given parameters.';
        info($message);
    }
}
