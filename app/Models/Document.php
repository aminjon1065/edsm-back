<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'title_document',
        'description_document',
        'content',
        'region',
        'department',
        'status',
        'type',
        'importance',
        'created_user_id',
        'updated_user_id',
        'date_done',
        'created_date',
        'updated_date'
    ];
    protected $casts =
        [
            'importance' => 'boolean',
            'date_done' => 'date',
            'created_date' => 'datetime',
            'updated_date' => 'datetime',
            'uuid' => 'string'
        ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function file(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function mail(): HasMany
    {
        return $this->hasMany(Mail::class);
    }

    public function openedMail(): HasMany
    {
        return $this->hasMany(OpenedMail::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(DocumentHistory::class);
    }

    public function replyTo(): HasMany
    {
        return $this->hasMany(ReplyTo::class);
    }
}
