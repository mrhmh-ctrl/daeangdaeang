<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $table = 'posts';

    protected $fillable = [
        'board_id',
        'member_id',
        'category_id',
        'title',
        'content',
        'writer_name',
        'writer_ip',
        'is_notice',
        'is_secret',
        'secret_password',
        'view_count',
        'recommend_count',
        'unrecommend_count',
        'comment_count',
        'file_count',
        'parent_id',
        'depth',
        'sort_order',
        'status',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class, 'board_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function category()
    {
        return $this->belongsTo(BoardCategory::class, 'category_id');
    }

    public function files()
    {
        return $this->hasMany(PostFile::class, 'post_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Y');
    }

    public function scopeNotice($query)
    {
        return $query->where('is_notice', 'Y');
    }
}
