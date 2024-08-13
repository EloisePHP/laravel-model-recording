<?php

namespace Eloise\DataAudit\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $user_id
 * @property int|null $eloise_audit_action_id
 * @property string $action
 * @property string $source_class
 * @property int $source_id
 * @property string $target_class
 * @property int $target_id
 * @property string $message
 * @property string $version
 * @property array $changes
 * @property array $serialized_data
 * @property string $link
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Audit extends Model
{
    use HasFactory;

    protected $table = 'eloise_audit';

    protected $fillable = [
        'user_id',
        'eloise_audit_action_id',
        'action',
        'source_class',
        'source_id',
        'target_class',
        'target_id',
        'target_class_identifier',
        'message',
        'version',
        'changes',
        'link',
        'serialized_data',
    ];

    protected $casts = [
        'changes' => 'array',
        'serialized_data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setAuditAction(): BelongsTo
    {
        return $this->belongsTo(AuditAction::class, 'eloise_audit_action_id');
    }
}
