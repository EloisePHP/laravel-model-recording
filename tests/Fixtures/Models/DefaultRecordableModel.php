<?php

namespace Eloise\RecordModel\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Eloise\RecordModel\Contracts\RecordableModel;
use Eloise\RecordModel\Traits\RecordableModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DefaultRecordableModel extends Model implements RecordableModel
{
    use HasFactory;
    use RecordableModelTrait;

    protected $table = 'test_eloise_recordable_model';

    protected $fillable = [
        'test_name',
    ];
}
