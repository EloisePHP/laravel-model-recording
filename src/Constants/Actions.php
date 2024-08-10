<?php

namespace Eloise\DataAudit\Constants;

class Actions
{
    public const array DEFAULT_ACTIONS = [
        self::ACTION_CREATED => self::ACTION_CREATED,
        self::ACTION_UPDATED => self::ACTION_UPDATED,
        self::ACTION_DELETED => self::ACTION_DELETED,
        self::ACTION_FORCE_DELETED => self::ACTION_FORCE_DELETED,
        self::ACTION_RESTORED => self::ACTION_RESTORED,
    ];
    public const string ACTION_CREATED = 'created';
    public const string ACTION_UPDATED = 'updated';
    public const string ACTION_DELETED = 'deleted';
    public const string ACTION_FORCE_DELETED = 'force_deleted';
    public const string ACTION_RESTORED = 'restored';
    public const string ACTION_TRANSFORMED = 'transformed';
}
