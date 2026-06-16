<?php

namespace App\Models\DogDict;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'dog_dict_categories';

    protected $fillable = ['name', 'slug', 'url', 'sort', 'is_active'];

    public function dogs()
    {
        return $this->hasMany(Dog::class, 'category_id');
    }
}
