<?php

namespace Eloise\DataAudit\Console\Commands;

use Eloise\DataAudit\Constants\Headers;
use Eloise\DataAudit\Queries\AuditQueries;
use Eloise\DataAudit\Services\AuditableModelsFromProject;
use Eloise\DataAudit\Services\LoadAuditableClassFromArray;
use Illuminate\Console\Command;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\multisearch;
use function Laravel\Prompts\search;
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
    protected $description = 'Get all auditable models';

    /**
     * @throws \Exception
     */
    public function handle(
        AuditableModelsFromProject $auditableModelsFromProject
    ): void
    {
        $modelName = $this->argument('modelName');
        if ($modelName === null) {
            info('You must provide a model as an argument, for instance:');
            info($this->signature);
            return;
        }

        info('Loading Auditable Models');
        $auditableModels = $auditableModelsFromProject->getAuditableModels();
        foreach ($auditableModels as $auditableModel) {
            if($auditableModel['short_name'] === $modelName) {
                $modelClassName = $auditableModel['class_name'];
            }
            $load = new LoadAuditableClassFromArray();
            $load->loadAuditableClass($auditableModel);
        }

        $modelId = $this->option('modelId');
        $userId = $this->option('userId');

        $headers = Headers::AUDIT_HEADERS;

        $this->auditQueries->getAuditFromUserAndModelId($modelClassName, $modelId, $userId, function ($audits) use ($headers, &$rows) {
            $rows = [];
            foreach ($audits as $audit) {
                $rows[] = $audit->toArrayForTable();
            }

            info('Ordered from the most recent');

            table(
                headers: $headers,
                rows: $rows
            );

            if (!confirm('Do you want to load more data?', true)) {
                return false;
            }
        });
    }
}
