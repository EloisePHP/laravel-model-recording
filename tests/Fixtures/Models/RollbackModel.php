<?php

namespace Eloise\DataAudit\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Eloise\DataAudit\Contracts\AuditableModel;
use Eloise\DataAudit\Traits\AuditableModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RollbackModel extends Model implements AuditableModel
{
    use HasFactory;
    use AuditableModelTrait;

    protected $table = 'test_eloise_rollback_model';

    protected $fillable = [
        'test_name',
        'test_description',
        'test_int',
        'test_array',
    ];

    protected $casts = [
        'test_array' => 'array',
    ];
}
