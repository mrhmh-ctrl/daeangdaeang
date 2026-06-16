<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $table = 'comments';

    protected $fillable = [
        'post_id',
        'member_id',
        'parent_id',
        'depth',
        'content',
        'writer_name',
        'writer_ip',
        'is_secret',
        'recommend_count',
        'status',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Y');
    }
}
