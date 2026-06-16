<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Member extends Authenticatable
{
    use SoftDeletes, Notifiable;

    protected $table = 'member_list';

    protected $fillable = [
        'user_id',
        'password',
        'name',
        'nick',
        'email',
        'hp',
        'birth',
        'sex',
        'zip',
        'addr1',
        'addr2',
        'level',
        'mailer_opt',
        'profile_image',
        'memo',
        'social_type',
        'social_id',
        'is_admin',
        'is_manager',
        'status',
        'block_memo',
        'block_until',
        'login_ip',
        'login_at',
        'leave_at',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'login_at'          => 'datetime',
            'block_until'       => 'datetime',
            'leave_at'          => 'datetime',
            'birth'             => 'date',
        ];
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'member_id');
    }

    public function pets()
    {
        return $this->hasMany(MemberPet::class, 'member_id')->orderBy('sort_order');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'member_id');
    }

    public function isAdmin()
    {
        return $this->is_admin === 'Y';
    }

    public function getLevelLabelAttribute()
    {
        return match((int)$this->level) {
            1  => '준회원',
            2  => '정회원',
            3  => '우수회원',
            4  => '특별회원',
            9  => '매니저',
            10 => '관리자',
            default => 'LV.'.$this->level,
        };
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Y');
    }
}
