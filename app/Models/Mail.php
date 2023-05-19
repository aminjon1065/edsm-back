<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mail extends Model
{
    use HasFactory;

    protected $fillable = [
        'to',
        'from',
        'send_date',
    ];

    protected $casts = [
        'send_date' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function document():HasMany
    {
        return  $this->hasMany(Document::class);
    }
}
