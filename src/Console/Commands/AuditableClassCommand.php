<?php

namespace Eloise\DataAudit\Console\Commands;

use Eloise\DataAudit\Services\AuditableModelsFromProject;
use Eloise\DataAudit\Services\LoadAuditableClassFromArray;
use Illuminate\Console\Command;
use Laravel\Prompts;

use function Laravel\Prompts\info;
use function Laravel\Prompts\table;

class AuditableClassCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'eloise:audit:class';

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
        info('Getting all Auditable Models.');

        $headers = [
            'Class name',
            'Short classname',
            'Default',
            'Active',
            'Version',
            'Source Class',
        ];

        $auditableModels = $auditableModelsFromProject->getAuditableModels();
        table(
            headers: $headers,
            rows: $auditableModels
        );
        foreach ($auditableModels as $auditableModel) {
            $load = new LoadAuditableClassFromArray();
            $load->loadAuditableClass($auditableModel);
        }

        info('All these classes were added to the database.');

        /*
        foreach ($auditableModels as $auditableModel) {
            $load = new LoadAuditableClassFromArray();
            $auditableModel = $load->loadAuditableClass($auditableModel);
            $load->loadActions($auditableModel);
        }

        info('Getting all Auditable Models.')
        */
    }
}
