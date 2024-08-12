<?php

namespace Eloise\DataAudit\Traits;

use Eloise\DataAudit\Constants\Actions;
use Eloise\DataAudit\Events\LoggingAuditEvent;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;
use Webmozart\Assert\Assert;

/**
 * This is a Trait that must be used on a class extending a Model
 *
 * @mixin Model
 */
trait DefaultModelOperationsTrait
{
    protected static function bootDefaultModelOperationsTrait(): void
    {
        //parent::boot();

        Assert::subclassOf(static::class, Model::class, static::errorMessage());
        
        static::created(fn($model) => static::logAuditEvent($model, Actions::ACTION_CREATED));
        static::updated(fn($model) => static::logAuditEvent($model, Actions::ACTION_UPDATED));
        static::deleted(fn($model) => static::logAuditEvent($model, Actions::ACTION_DELETED));
    }

    protected static function logAuditEvent($model, string $action): void
    {
        event(new LoggingAuditEvent($model, $action));
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
}
