<?php

namespace Eloise\DataAudit\Loaders;

use Eloise\DataAudit\Models\AuditableClass;

class LoadAuditableClass
{
    /**
     * Load or create an auditable class from an array.
     *
     * @param array{
     *     class_name: string,
     *     short_name: string,
     *     default: bool,
     *     active: bool,
     *     version: string,
     *     auditable_id?: int
     * } $auditModel
     * @return array{
     *     class_name: string,
     *     short_name: string,
     *     default: bool,
     *     active: bool,
     *     version: string,
     *     auditable_id: int
     * }
     */
    public function load(array $auditModel): array
    {
        $auditableClass = AuditableClass::updateOrCreate(
            ['class_name' => $auditModel['class_name']],
            [
                'name' => $auditModel['short_name'],
                'version' => $auditModel['version'],
            ]
        );

        $auditModel['auditable_id'] = (int) $auditableClass->id;

        return $auditModel;
    }
}
