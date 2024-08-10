<?php

namespace Eloise\DataAudit\Traits;

/**
 * This is a Trait that must be used in a Model you want to Audit, it sets up the basic functionalities of the DataAudit Package.
 */
trait AuditableModelTrait
{
    use DefaultModelOperationsTrait;

    public function getSourceModelClass(): string
    {
        return self::class;
    }

    public function defaultAudit(): bool
    {
        return true;
    }

    public function activeAudit(): bool
    {
        return true;
    }

    public function versionAudit(): string
    {
        return 'v1_0';
    }
}
