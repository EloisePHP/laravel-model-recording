<?php

namespace Eloise\DataAudit\Models;

use Eloise\DataAudit\Constants\AuditableProperties;
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
 * @property array $diff
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
        'diff',
        'link',
        'serialized_data',
    ];

    protected $casts = [
        'diff' => 'array',
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
            $this->attributesChanged(),
            $this->version,
            $this->created_at,
            $this->updated_at,
        ];
    }

    public function attributesChanged(): string
    {
        $added = 0;
        foreach ($this->diff as $diff) {
            if ($diff[AuditableProperties::ORIGINAL_VALUE] === null) {
                $added += 1;
            }
        }
        $updated = count($this->diff) - $added;

        $messageAdded = ($added > 0) ? sprintf("%s %s", $added, ' added') : '';
        $messageAdded = (!empty($messageAdded) && $updated > 0) ? sprintf("%s%s", $messageAdded, "\n") : $messageAdded;
        $messageUpdated = ($updated > 0 ) ? sprintf("%s%s %s", $messageAdded, $updated, ' updated') : $messageAdded;

        return !empty($messageUpdated) ? $messageUpdated : 'No changes';
    }
}
