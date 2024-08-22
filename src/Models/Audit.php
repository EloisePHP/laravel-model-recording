<?php

namespace Eloise\DataAudit\Models;

use Eloise\DataAudit\Traits\Models\AuditRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
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
    use AuditRelationships;

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

    /**
     * @return array<int, mixed>
     */
    public function toArrayForTable(): array
    {
        return [
            $this->id,
            $this->user_id,
            $this->action,
            $this->source_class,
            $this->source_id,
            $this->target_class,
            $this->target_id,
            $this->message,
            $this->version,
            $this->created_at,
            $this->updated_at,
        ];
    }
}
