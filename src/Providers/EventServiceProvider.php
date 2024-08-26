<?php

namespace Eloise\RecordModel\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as IlluminateEventServiceProvider;
use Eloise\RecordModel\Events\RecordEvent;
use Eloise\RecordModel\Listeners\LoggingRecordListener;

class EventServiceProvider extends IlluminateEventServiceProvider
{
    /**
    * @var array<string, array<int, string>>
    */
    protected $listen = [
        RecordEvent::class => [
            LoggingRecordListener::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
