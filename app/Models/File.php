<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable =
        [
            'name_file',
            'size',
            'extension_file',
            'document_id',
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

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
