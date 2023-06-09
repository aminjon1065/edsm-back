<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogAuthUser extends Model
{
    use HasFactory;

    protected $fillable =
        [
            'user_id',
            'last_auth',
        ];
    protected $casts = [
        'last_auth' => 'datetime',
    ];

    //Отношение пользователи-логи авторизации
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
