<?php

namespace Eloise\DataAudit\Events;

use Eloise\DataAudit\Contracts\AuditableModel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuditEvent
{
    use Dispatchable, SerializesModels, InteractsWithSockets;

    public function __construct(
        protected AuditableModel $auditableModel,
        protected string $action
    ) {
    }

    public function getAuditableModel(): AuditableModel
    {
        return $this->auditableModel;
    }

    public function getAuditAction(): string
    {
        return $this->action;
    }

    public function setAuditableModel(AuditableModel $auditableModel): self
    {
        $this->auditableModel = $auditableModel;

        return $this;
    }
}
