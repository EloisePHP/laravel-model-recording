<?php

namespace Eloise\DataAudit\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as IlluminateEventServiceProvider;
use Eloise\DataAudit\Events\AuditEvent;
use Eloise\DataAudit\Events\RewindEvent;
use Eloise\DataAudit\Listeners\LoggingAuditListener;
use Eloise\DataAudit\Listeners\RewindListener;

class EventServiceProvider extends IlluminateEventServiceProvider
{
    /**
    * @var array<string, array<int, string>>
    */
    protected $listen = [
        AuditEvent::class => [
            LoggingAuditListener::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
