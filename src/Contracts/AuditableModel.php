<?php

namespace Eloise\DataAudit\Contracts;

/**
 * This a Contract for models only, every Model inside App\Models implementing this contract will be added to the audit_class table.
 * If you are starting to use this Package you should use the Trait InitialDefaultActiveAuditTrait.
 */
interface AuditableModel
{
    public function getSourceModelClass(): string;

    public function defaultAudit(): bool;
    public function activeAudit(): bool;
    public function versionAudit(): string;

    //Methods comming from Model class
    public function getDirty();

}
