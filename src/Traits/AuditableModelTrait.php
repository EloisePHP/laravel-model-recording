<?php

namespace Eloise\DataAudit\Traits;

use Eloise\DataAudit\Constants\Actions;
use Eloise\DataAudit\Events\AuditEvent;
use Eloise\DataAudit\Models\Audit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Webmozart\Assert\Assert;

/**
 * This is a Trait that must be used in a Model you want to Audit,
 * it sets up the basic functionalities of the DataAudit Package.
 */
trait AuditableModelTrait
{
    public function getSourceModelClass(): string
    {
        return self::class;
    }

    public function versionAudit(): string
    {
        return 'v1_0';
    }

    protected static function bootAuditableModelTrait(): void
    {
        Assert::subclassOf(static::class, Model::class, static::errorMessage());
        static::created(fn($model) => static::logAuditEvent($model, Actions::ACTION_CREATED));
        static::updated(fn($model) => static::logAuditEvent($model, Actions::ACTION_UPDATED));
        static::deleted(fn($model) => static::logAuditEvent($model, Actions::ACTION_DELETED));
    }

    protected static function logAuditEvent($model, string $action): void
    {
        event(new AuditEvent($model, $action));
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

    public function auditsAsSource(): MorphMany
    {
        return $this->morphMany(Audit::class, 'source', 'source_class', 'source_id');
    }

    /**
     * Get all audits where this post is the target.
     */
    public function auditsAsTarget(): MorphMany
    {
        return $this->morphMany(Audit::class, 'target', 'target_class', 'target_id');
    }

    /**
    * This is more efficient than using polymorphic relations
    */
    public function audits()
    {
        return Audit::where(function ($query) {
            $query->where('source_class', self::class)
                ->where('source_id', $this->id);
        })->orWhere(function ($query) {
            $query->where('target_class', self::class)
                ->where('target_id', $this->id);
        })->get();
    }
}
