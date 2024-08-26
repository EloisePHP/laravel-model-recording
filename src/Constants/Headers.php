<?php

namespace Eloise\RecordModel\Constants;

class Headers
{
    public const array RECORDABLE_CLASSES_HEADER = [
        'Class name',
        'Short classname',
        'Default',
        'Active',
        'Version',
        'Source Class',
    ];

    public const array RECORD_HEADERS_FOR_COMMAND = [
        'id',
        'user_id',
        'action',
        'source_class',
        'source_id',
        'target_class',
        'target_id',
        'fields changed',
        'version',
        'created_at',
        'updated_at',
    ];

    public const array RECORD_RELATIONS_HEADER = [
        'source_class',
        'target_class',
        'type'
    ];
}
