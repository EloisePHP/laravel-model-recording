<?php

namespace Eloise\DataAudit\Constants;

class PathNames
{
    // This is where all your models are located (you can change this path to 'App' if you have a Bundle architecture in your project)
    public const string PROJECT_MODELS = 'App\\Models\\';

    public const string AUDITABLE_CONTRACT = 'Eloise\DataAudit\Contracts\AuditableModel';
}
