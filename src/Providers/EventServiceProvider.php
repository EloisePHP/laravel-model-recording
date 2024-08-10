<?php

namespace Eloise\DataAudit\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as IlluminateEventServiceProvider;
use Eloise\DataAudit\Events\LoggingAuditEvent;
use Eloise\DataAudit\Listeners\LoggingAuditListener;

class EventServiceProvider extends IlluminateEventServiceProvider
{
    protected $listen = [
        LoggingAuditEvent::class => [
            [LoggingAuditListener::class, 'onAudit'],
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
