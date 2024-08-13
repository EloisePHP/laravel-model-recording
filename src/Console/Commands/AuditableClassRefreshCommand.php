<?php

namespace Eloise\DataAudit\Console\Commands;

use Eloise\DataAudit\Services\AuditableModelsFromProject;
use Eloise\DataAudit\Services\LoadAuditableClassFromArray;
use Illuminate\Console\Command;

class AuditableClassRefreshCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'audit:class:refresh';

    /**
     * @var string
     */
    protected $description = 'Refresh all auditable classes config';

    /**
     * @throws \Exception
     */
    public function handle(
        AuditableModelsFromProject $auditableModelsFromProject
    ): void
    {
        $this->info('Refreshing all Auditable Models Config.');

        $headers = [
            'class_name',
            'short_name',
            'default',
            'active',
            'version',
            'source_class',
        ];

        $auditableModels = $auditableModelsFromProject->getAuditableModels();
        $this->info('All Auditable Models');
        $this->table($headers, $auditableModels);

        foreach ($auditableModels as $auditableModel) {
            $load = new LoadAuditableClassFromArray();
            $auditableModel = $load->loadAuditableClass($auditableModel);
            $load->loadActions($auditableModel);
        }

        $this->info('All Auditable Models classes and Auditable Actions were saved in the database.');
    }
}
