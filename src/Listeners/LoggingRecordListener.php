<?php

namespace Eloise\RecordModel\Listeners;

use Eloise\RecordModel\Events\RecordEvent;
use Eloise\RecordModel\Managers\RecordModelManager;
use Illuminate\Contracts\Queue\ShouldQueue;

//class LoggingRecordListener implements ShouldQueue
class LoggingRecordListener
{
    public function handle(RecordEvent $event): void
    {
        $recordableModel = $event->getRecordableModel();
        $action = $event->getRecordAction();

        $recordManager = new RecordModelManager($recordableModel, $action);
        $recordManager->createRecords();
    }
}
