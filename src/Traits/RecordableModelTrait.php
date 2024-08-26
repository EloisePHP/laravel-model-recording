<?php

namespace Eloise\RecordModel\Traits;

use Eloise\RecordModel\Constants\Actions;
use Eloise\RecordModel\Events\RecordEvent;
use Eloise\RecordModel\Models\Record;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Webmozart\Assert\Assert;

/**
 * This is a Trait that must be used in a Model you want to Record,
 * it sets up the basic functionalities of the RecordModel Package.
 */
trait RecordableModelTrait
{
    public function getSourceModelClass(): string
    {
        return self::class;
    }

    public function versionRecord(): string
    {
        return 'v1_0';
    }

    protected static function bootRecordableModelTrait(): void
    {
        Assert::subclassOf(static::class, Model::class, static::errorMessage());
        static::created(fn($model) => static::logRecordEvent($model, Actions::ACTION_CREATED));
        static::updated(fn($model) => static::logRecordEvent($model, Actions::ACTION_UPDATED));
        static::deleted(fn($model) => static::logRecordEvent($model, Actions::ACTION_DELETED));
    }

    protected static function logRecordEvent($model, string $action): void
    {
        event(new RecordEvent($model, $action));
    }

    protected static function errorMessage(): string
    {
        return sprintf(
            '%s must extend %s to use the %s trait.',
            static::class,
            Model::class,
            __TRAIT__
        );
    }

    public function recordsAsSource(): MorphMany
    {
        return $this->morphMany(Record::class, 'source', 'source_class', 'source_id');
    }

    /**
     * Get all records where this post is the target.
     */
    public function recordsAsTarget(): MorphMany
    {
        return $this->morphMany(Record::class, 'target', 'target_class', 'target_id');
    }

    /**
    * This is more efficient than using polymorphic relations
    */
    public function records()
    {
        return Record::where(function ($query) {
            $query->where('source_class', self::class)
                ->where('source_id', $this->id);
        })->orWhere(function ($query) {
            $query->where('target_class', self::class)
                ->where('target_id', $this->id);
        })->get();
    }
}
