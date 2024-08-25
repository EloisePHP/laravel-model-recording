<?php

namespace Eloise\DataAudit\Facades;

use Illuminate\Support\Facades\Facade;

class Rollback extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rollback.manager.factory';
    }
}
