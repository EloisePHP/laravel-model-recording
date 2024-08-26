<?php

namespace Eloise\RecordModel\Constants;

class PathNames
{
    public const string RECORDABLE_CONTRACT = 'Eloise\RecordModel\Contracts\RecordableModel';

    public function getProjectModelsPath(): string
    {
        return config('paths.models_folder');
    }

    public function getPrefixClassName(): string
    {
        return config('paths.prefix_class_name');
    }
}
