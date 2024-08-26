<?php

namespace Eloise\RecordModel\Models;

use Eloise\RecordModel\Constants\RecordableProperties;
use Eloise\RecordModel\Traits\Models\RecordRelationships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $user_id
 * @property int|null $eloise_record_action_id
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
class Record extends Model
{
    use RecordRelationships;

    protected $table = 'eloise_record';

    protected $fillable = [
        'user_id',
        'eloise_record_action_id',
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
            if ($diff[RecordableProperties::ORIGINAL_VALUE] === null) {
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
