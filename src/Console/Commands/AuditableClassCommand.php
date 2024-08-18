<?php

namespace Eloise\DataAudit\Console\Commands;

use Eloise\DataAudit\Constants\Headers;
use Eloise\DataAudit\Services\AuditableModelsFromProject;
use Eloise\DataAudit\Services\LoadAuditableClassFromArray;
use Illuminate\Console\Command;

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
    ): void {
        info('Getting all Auditable Models.');

        $auditableModels = $auditableModelsFromProject->getAuditableModels();
        table(
            headers: Headers::AUDITABLE_CLASSES_HEADER,
            /** @phpstan-ignore-next-line */
            rows: $auditableModels
        );
        foreach ($auditableModels as $auditableModel) {
            $load = new LoadAuditableClassFromArray();
            $load->loadAuditableClass($auditableModel);
        }

        info('All these classes were added to the database.');
    }
}
