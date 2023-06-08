<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentHistory extends Model
{
    use HasFactory;

    protected $fillable = ['document_id', 'sender_id', 'recipient_id', 'status'];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id')->select('id', 'full_name');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id')->select('id', 'full_name');
    }
}
