<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpenedMail extends Model
{
    use HasFactory;

    protected $fillable = [
        'mail_id',
        'user_id',
        'opened'
    ];


    public function mail(): BelongsTo
    {
        return $this->belongsTo(Mail::class);
    }
}
