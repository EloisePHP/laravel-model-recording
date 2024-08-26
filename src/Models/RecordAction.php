<?php

namespace Eloise\RecordModel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class RecordAction
 *
 * @property int $id
 * @property int $eloise_record_class_id
 * @property string $name
 * @property string $source_class
 * @property string|null $description
 * @property string $version
 * @property array|null $serialized_data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class RecordAction extends Model
{
    protected $table = 'eloise_record_action';

    protected $fillable = [
        'name',
        'eloise_record_class_id',
        'source_class',
        'target_class',
        'method',
        'description',
        'version',
        'serialized_data',
    ];

    protected $casts = [
        'serialized_data' => 'array',
    ];

    /**
     * @return BelongsTo<RecordableClass, RecordAction>
     */
    public function setRecordableClass(): BelongsTo
    {
        return $this->belongsTo(RecordedModel::class, 'eloise_record_class_id');
    }
}
