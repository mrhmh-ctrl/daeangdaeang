<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberPet extends Model
{
    protected $table = 'member_pets';

    protected $fillable = [
        'member_id',
        'breed',
        'name',
        'gender',
        'birth',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'birth' => 'date',
        ];
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
