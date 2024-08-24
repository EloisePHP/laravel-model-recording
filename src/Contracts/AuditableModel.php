<?php

namespace Eloise\DataAudit\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * This a Contract for models only, every Model inside App\Models
 * implementing this contract will be added to the audit_class table.
 * If you are starting to use this Package you should use the Trait AuditableModelTrait.
 */
interface AuditableModel
{
    public function getSourceModelClass(): string;

    public function versionAudit(): string;

    public function auditsAsSource(): MorphMany;

    public function auditsAsTarget(): MorphMany;

    // Methods from Model Class

    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    public function getKeyName();

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function setAttribute($key, $value);

    /**
     * Clone the model into a new, non-existing instance.
     *
     * @param  array|null  $except
     * @return static
     */
    public function replicate(?array $except = null);

    /**
     * @return array<string, mixed>
     */
    public function getDirty();

    /**
     * @param string $attribute
     * @return mixed
     */
    public function getOriginal(string $attribute);

    /**
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = []);
}
