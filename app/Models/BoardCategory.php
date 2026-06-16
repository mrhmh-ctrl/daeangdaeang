<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardCategory extends Model
{
    protected $table = 'board_categories';

    protected $fillable = [
        'board_id',
        'category_name',
        'sort_order',
        'status',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class, 'board_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Y');
    }
}
