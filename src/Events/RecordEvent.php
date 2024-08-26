<?php

namespace Eloise\RecordModel\Events;

use Eloise\RecordModel\Contracts\RecordableModel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RecordEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public function __construct(
        protected RecordableModel $recordableModel,
        protected string $action
    ) {
    }

    public function getRecordableModel(): RecordableModel
    {
        return $this->recordableModel;
    }

    public function getRecordAction(): string
    {
        return $this->action;
    }

    public function setRecordableModel(RecordableModel $recordableModel): self
    {
        $this->recordableModel = $recordableModel;

        return $this;
    }
}
