<?php

namespace Eloise\DataAudit\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class AuditAction
 *
 * @property int $id
 * @property int $eloise_audit_class_id
 * @property string $name
 * @property string $source_class
 * @property string|null $description
 * @property string $version
 * @property array|null $serialized_data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class AuditAction extends Model
{
    protected $table = 'eloise_audit_action';

    protected $fillable = [
        'name',
        'eloise_audit_class_id',
        'source_class',
        'target_class',
        'description',
        'version',
        'serialized_data',
    ];

    protected $casts = [
        'serialized_data' => 'array',
    ];

    /**
     * @return BelongsTo<AuditableClass, AuditAction>
     */
    public function setAuditableClass(): BelongsTo
    {
        return $this->belongsTo(AuditableClass::class, 'eloise_audit_class_id');
    }
}
