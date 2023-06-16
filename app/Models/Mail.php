<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'to',
        'from',
        'reply',
        'from_user_name',
        'document_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class)
            ->with(['file', 'history.recipient' => function ($query) {
                $query->select('id', 'full_name');
            }]);
    }

    public function openedMail(): HasMany
    {
        return $this->hasMany(OpenedMail::class);
    }
    public function replyTo(): HasOne
    {
        return $this->hasOne(ReplyTo::class);
    }
    public function mailReply(): HasOne
    {
        return $this->hasOne(ReplyTo::class, 'mail_id');
    }
    public function mailReplyId(): HasOne
    {
        return $this->hasOne(ReplyTo::class, 'mail_reply_id');
    }
}
