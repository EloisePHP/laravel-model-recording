<?php

namespace Eloise\DataAudit\Tests\Fixtures\Models;

use Eloise\DataAudit\Contracts\AuditableModel;
use Eloise\DataAudit\Traits\AuditableModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model implements AuditableModel
{
    use HasFactory;
    use AuditableModelTrait;

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
