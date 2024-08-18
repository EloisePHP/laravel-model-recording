<?php

namespace Eloise\DataAudit\Constants;

class Headers
{
    public const array AUDITABLE_CLASSES_HEADER = [
        'Class name',
        'Short classname',
        'Default',
        'Active',
        'Version',
        'Source Class',
    ];

    public const array AUDIT_HEADERS = [
        'id',
        'user_id',
        'action',
        'source_class',
        'source_id',
        'target_class',
        'target_id',
        'message',
        'version',
        'created_at',
        'updated_at',
    ];
}
