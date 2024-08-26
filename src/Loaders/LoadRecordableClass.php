<?php

namespace Eloise\RecordModel\Loaders;

use Eloise\RecordModel\Models\RecordedModel;

class LoadRecordableClass
{
    /**
     * Load or create an recordable class from an array.
     *
     * @param array{
     *     class_name: string,
     *     short_name: string,
     *     default: bool,
     *     active: bool,
     *     version: string,
     *     recordable_id?: int
     * } $recordModel
     * @return array{
     *     class_name: string,
     *     short_name: string,
     *     default: bool,
     *     active: bool,
     *     version: string,
     *     recordable_id: int
     * }
     */
    public function load(array $recordModel): array
    {
        $recordableClass = RecordedModel::updateOrCreate(
            ['class_name' => $recordModel['class_name']],
            [
                'name' => $recordModel['short_name'],
                'version' => $recordModel['version'],
            ]
        );

        $recordModel['recordable_id'] = (int) $recordableClass->id;

        return $recordModel;
    }
}
