<?php

namespace Eloise\DataAudit\Constants;

class PathNames
{
    public const string AUDITABLE_CONTRACT = 'Eloise\DataAudit\Contracts\AuditableModel';

    public function getProjectModelsPath(): string
    {
        return config('paths.models_folder');
    }

    public function getPrefixClassName(): string
    {
        return config('paths.prefix_class_name');
    }
}
