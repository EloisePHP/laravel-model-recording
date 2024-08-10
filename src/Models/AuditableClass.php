<?php

namespace Eloise\DataAudit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $class_name
 * @property bool $default
 * @property bool $active
 * @property string $version
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class AuditableClass extends Model
{
    protected $table = 'eloise_auditable_class';

    protected $fillable = [
        'name',
        'class_name',
        'default',
        'active',
        'version',
    ];

    public function getLastVersion(): string
    {
        return $this->version;
    }
}
