<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Board extends Model
{
    protected $table = 'boards';

    protected $fillable = [
        'board_id',
        'board_name',
        'board_type',
        'skin',
        'header_inc',
        'footer_inc',
        'sort_order',
        'status',
        // 권한
        'list_level',
        'read_level',
        'write_level',
        'comment_level',
        'download_level',
        // 글쓰기 설정
        'use_editor',
        'use_file',
        'file_count',
        'file_size',
        'use_secret',
        'use_notice',
        'use_recommend',
        'use_category',
        // 댓글 설정
        'use_comment',
        'use_comment_reply',
        // 목록 설정
        'list_count',
        'comment_count',
        'new_hour',
        'use_thumb',
        'use_view_count',
        'use_num',
        'use_title',
        'use_writer',
        'use_date',
        'use_recommend_col',
    ];

    // 게시글
    public function posts()
    {
        return $this->hasMany(Post::class, 'board_id');
    }

    // 카테고리
    public function categories()
    {
        return $this->hasMany(BoardCategory::class, 'board_id');
    }

    // 사용중인 게시판만
    public function scopeActive($query)
    {
        return $query->where('status', 'Y');
    }

    // 유형명
    public function getBoardTypeLabelAttribute()
    {
        return match($this->board_type) {
            1 => '일반',
            2 => '갤러리',
            3 => 'QnA',
            default => '일반',
        };
    }

    // 권한명
    public function getLevelLabelAttribute($level)
    {
        return match((int)$level) {
            0 => '전체',
            1 => '회원',
            2 => '관리자',
            default => 'LV.'.$level,
        };
    }
}
