<?php

namespace Eloise\DataAudit\Console\Commands;

use Eloise\DataAudit\Constants\Headers;
use Eloise\DataAudit\Contracts\AuditableModel;
use Eloise\DataAudit\Loaders\LoadActions;
use Eloise\DataAudit\Services\AuditableModelsFromProject;
use Eloise\DataAudit\Loaders\LoadAuditableClass;
use Illuminate\Console\Command;

use function Laravel\Prompts\info;
use function Laravel\Prompts\table;

class AuditableClassCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'eloise:audit:refresh';

    /**
     * @var string
     */
    protected $description = 'Instatiate all Auditable Classes and Actions to be auditable';

    /**
     * @throws \Exception
     */
    public function handle(
        AuditableModelsFromProject $auditableModelsFromProject
    ): void {
        info('Getting all Auditable Models.');

        $auditableModels = $auditableModelsFromProject->toArray();
        table(
            headers: Headers::AUDITABLE_CLASSES_HEADER,
            /** @phpstan-ignore-next-line */
            rows: $auditableModels
        );
        foreach ($auditableModels as $auditableModel) {
            $load = new LoadAuditableClass();
            $load->load($auditableModel);
        }

        $auditableModels = $auditableModelsFromProject->getAuditableModels();

        /** @var AuditableModel $auditableModel */
        foreach ($auditableModels as $auditableModel) {
             $loader = new LoadActions($auditableModel);
             $loader->load();
        }

        info('All these classes were added to the database.');
    }
}
