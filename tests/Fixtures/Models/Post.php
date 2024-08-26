<?php

namespace Eloise\RecordModel\Tests\Fixtures\Models;

use Eloise\RecordModel\Contracts\RecordableModel;
use Eloise\RecordModel\Traits\RecordableModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model implements RecordableModel
{
    use HasFactory;
    use RecordableModelTrait;

    protected $table = 'test_eloise_post';

    protected $fillable = ['title', 'body', 'user_id',];

    /**
     * Get the comments for the post.
     */
    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
