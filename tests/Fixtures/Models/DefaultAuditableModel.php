<?php

namespace Eloise\DataAudit\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Eloise\DataAudit\Contracts\AuditableModel;
use Eloise\DataAudit\Traits\AuditableModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DefaultAuditableModel extends Model implements AuditableModel
{
    use HasFactory;
    use AuditableModelTrait;

    protected $table = 'test_eloise_auditable_model';

    protected $fillable = [
        'test_name',
    ];
}
