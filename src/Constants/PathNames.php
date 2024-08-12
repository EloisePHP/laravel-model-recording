<?php

namespace Eloise\DataAudit\Constants;

use Illuminate\Contracts\Config\Repository;

class PathNames
{
    public const string AUDITABLE_CONTRACT = 'Eloise\DataAudit\Contracts\AuditableModel';

    //public const string MODELS_FOLDER = 'Models';

    // This is where all your models are located (you can change this path to 'App' if you have a Bundle architecture in your project)
    public function getProjectModelsPath(): string
    {
        return config('paths.models_folder');
    }

    public function getPrefixClassName(): string
    {
        return config('paths.prefix_class_name');
    }

}
