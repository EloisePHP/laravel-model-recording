<?php

namespace Eloise\DataAudit\Constants;

class Actions
{
    public const array DEFAULT_ACTIONS = [
        self::ACTION_CREATED,
        self::ACTION_UPDATED,
        self::ACTION_DELETED,
    ];
    public const string ACTION_CREATED = 'created';
    public const string ACTION_UPDATED = 'updated';
    public const string ACTION_DELETED = 'deleted';
}
