<?php

namespace Eloise\DataAudit\Listeners;

use Eloise\DataAudit\Events\LoggingAuditEvent;
use Eloise\DataAudit\Managers\AuditModelManager;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoggingAuditListener implements ShouldQueue
{
    public function __construct(
        protected AuditModelManager $auditModelManager
    )
    {
    }

    public function onAudit(LoggingAuditEvent $event): void
    {
        $auditableModel = $event->getAuditableModel();
        $action = $event->getAuditAction();

        $this->auditModelManager->createAudit($auditableModel, $action);
    }
}
