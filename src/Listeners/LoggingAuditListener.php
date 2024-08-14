<?php

namespace Eloise\DataAudit\Listeners;

use Eloise\DataAudit\Events\AuditEvent;
use Eloise\DataAudit\Managers\AuditModelManager;
use Illuminate\Contracts\Queue\ShouldQueue;

//class LoggingAuditListener implements ShouldQueue
class LoggingAuditListener
{
    public function __construct(
        protected AuditModelManager $auditModelManager
    )
    {
    }

    public function onAudit(AuditEvent $event): void
    {
        $auditableModel = $event->getAuditableModel();
        $action = $event->getAuditAction();

        $this->auditModelManager->createAudit($auditableModel, $action);
    }
}
