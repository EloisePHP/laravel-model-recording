<?php

namespace Eloise\RecordModel\Queries;

use Carbon\Carbon;
use Eloise\RecordModel\Constants\Actions;
use Eloise\RecordModel\Contracts\RecordableModel;

class RollbackQuery
{
    public function getCollectionOfDiffs(RecordableModel $model, Carbon|null $dateTime)
    {
        /** @var MorphToMany $morphToMany*/
        $morphToMany = $model->recordsAsSource();
         return $morphToMany->when($dateTime, function ($query) use ($dateTime) {
                                $query->where('created_at', '>', $dateTime);
         })
                            ->whereNull('target_class')
                            ->whereIn('action', [Actions::ACTION_CREATED, Actions::ACTION_UPDATED])
                            ->orderBy('created_at', 'desc')
                            ->orderBy('id', 'desc')
                            ->pluck('diff');
    }
}
