<?php

namespace Eloise\DataAudit\Traits\Models;

use Eloise\DataAudit\Models\AuditAction;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Auth\User;

trait AuditRelationships
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setAuditAction(): BelongsTo
    {
        return $this->belongsTo(AuditAction::class, 'eloise_audit_action_id');
    }

    public function source(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'source_class', 'source_id');
    }

    public function target(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'target_class', 'target_id');
    }
}
