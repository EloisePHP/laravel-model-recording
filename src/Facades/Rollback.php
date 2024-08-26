<?php

namespace Eloise\RecordModel\Facades;

use Illuminate\Support\Facades\Facade;

class Rollback extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'rollback.manager.factory';
    }
}
