<?php

namespace Eloise\DataAudit\Console\Commands;

use Eloise\DataAudit\Models\Audit;
use Eloise\DataAudit\Services\AuditableModelsFromProject;
use Eloise\DataAudit\Services\LoadAuditableClassFromArray;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;


class ActionRegisterCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'audit:action:register';

    /**
     * @var string
     */
    protected $description = 'Register all actions audited.';

    /**
     * @throws \Exception
     */
    public function handle(
        AuditableModelsFromProject $auditableModelsFromProject
    ): void
    {
        $this->info('Registering all actions audited.');

        $headers = [
            'action',
            'class_name',
            'short_name',
            'default',
            'active',
            'version',
            'source_class',
        ];
        $output = new ConsoleOutput();
    $total = Audit::count(); // Get the total count of records

    // Create a new progress bar instance
    $progressBar = new ProgressBar($output, $total);
    $progressBar->start();

    // Process audits in chunks
    Audit::chunk(1, function ($audits) use ($progressBar) {
        foreach ($audits as $audit) {
            // Process each audit here
            dump('chunked');

            // Advance the progress bar
            $progressBar->advance();
        }
    });
    }
}
