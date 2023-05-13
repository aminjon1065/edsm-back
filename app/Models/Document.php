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

    protected $fillable =
        [
            'title_document',
            'description_document',
            'content',
            'region',
            'status',
            'created_user_id',
            'updated_user_id',
            'created_date',
            'updated_date'
        ];

    protected $casts =
        [
            'created_date' => 'datetime',
            'updated_date' => 'datetime',
        ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function file(): HasMany
    {
        return $this->hasMany(File::class);
    }
}
