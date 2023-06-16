<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ReplyTo extends Model
{
    use HasFactory;

    protected $fillable = [
        'from',
        'reply_to',
        'mail_id',
        'document_id',
        'mail_reply_id'
    ];

    public function mail(): BelongsTo
    {
        return $this->belongsTo(Mail::class, 'mail_id');
    }
}
