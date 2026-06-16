<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostFile extends Model
{
    protected $table = 'post_files';

    protected $fillable = [
        'post_id',
        'member_id',
        'original_name',
        'stored_name',
        'file_path',
        'file_ext',
        'mime_type',
        'file_size',
        'is_image',
        'thumb_path',
        'download_count',
        'sort_order',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    // 파일 크기 MB 변환
    public function getFileSizeMbAttribute()
    {
        return round($this->file_size / 1024 / 1024, 2) . 'MB';
    }

    public function scopeImages($query)
    {
        return $query->where('is_image', 'Y');
    }
}
