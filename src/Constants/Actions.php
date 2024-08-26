<?php

namespace Eloise\RecordModel\Constants;

class Actions
{
    public const array DEFAULT_ACTIONS = [
        self::ACTION_CREATED,
        self::ACTION_UPDATED,
        self::ACTION_DELETED,
    ];
    public const string ACTION_CREATED = 'eloise_created';
    public const string ACTION_UPDATED = 'eloise_updated';
    public const string ACTION_DELETED = 'eloise_deleted';
}
