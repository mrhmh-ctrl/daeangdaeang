<?php

namespace App\Models\DogDict;

use Illuminate\Database\Eloquent\Model;

class Dog extends Model
{
    protected $table = 'dog_dicts';

    protected $fillable = [
        'category_id',
        'name',
        'content',
        'view_count',
        'search_count',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
