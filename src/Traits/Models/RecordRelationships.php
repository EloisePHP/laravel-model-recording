<?php

namespace Eloise\RecordModel\Traits\Models;

use Eloise\RecordModel\Models\RecordAction;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;

trait RecordRelationships
{
    /**
     * @return BelongsTo<User, static>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<RecordAction, static>
     */
    public function setRecordAction(): BelongsTo
    {
        return $this->belongsTo(RecordAction::class, 'eloise_record_action_id');
    }

    /**
     * @return MorphTo<Model, static>
     */
    public function source(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'source_class', 'source_id');
    }

    /**
     * @return MorphTo<Model, static>
     */
    public function target(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'target_class', 'target_id');
    }
}
