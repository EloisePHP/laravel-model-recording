<?php

namespace Eloise\DataAudit\Listeners;

use Eloise\DataAudit\Events\AuditEvent;
use Eloise\DataAudit\Managers\AuditModelManager;
use Illuminate\Contracts\Queue\ShouldQueue;

//class LoggingAuditListener implements ShouldQueue
class LoggingAuditListener
{
    public function handle(AuditEvent $event): void
    {
        $auditableModel = $event->getAuditableModel();
        $action = $event->getAuditAction();

        $auditManager = new AuditModelManager($auditableModel, $action);
        $auditManager->createAudits();
    }
}
