<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'full_name',
        'position',
        'department',
        'region',
        'rank',
        'avatar',
        'signature',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'role',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'uuid' => 'string',
    ];

//    Отношение логи авторизации пользователей
    public function logAuthUsers(): HasMany
    {
        return $this->hasMany(LogAuthUser::class);
    }

    public function lastLogAuth():HasOne
    {
        return  $this->hasOne(LogAuthUser::class)->latest();
    }
//    Отношение документы-пользователи
    public function document(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
