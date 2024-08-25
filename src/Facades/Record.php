<?php

namespace Eloise\DataAudit\Facades;

use Illuminate\Support\Facades\Facade;

class Record extends Facade
{
    /**
     * * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'record.manager.factory';
    }
}
