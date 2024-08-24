<?php

namespace Eloise\DataAudit\Queries;

use Carbon\Carbon;
use Eloise\DataAudit\Constants\Actions;
use Eloise\DataAudit\Contracts\AuditableModel;

class RollbackQuery
{
    public function getCollectionOfDiffs(AuditableModel $model, Carbon|null $dateTime)
    {
        /** @var MorphToMany $morphToMany*/
        $morphToMany = $model->auditsAsSource();
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
