<?php

namespace Eloise\DataAudit\Contracts;

/**
 * This a Contract for models only, every Model inside App\Models
 * implementing this contract will be added to the audit_class table.
 * If you are starting to use this Package you should use the Trait AuditableModelTrait.
 */
interface AuditableModel
{
    public function getSourceModelClass(): string;

    public function versionAudit(): string;

    /**
     * Methods comming from Model class
     *
     * @return array<string, mixed>
     */
    public function getDirty();

    /**
     * Get the original value of an attribute.
     *
     * @param string $attribute
     * @return mixed
     */
    public function getOriginal(string $attribute);
}
