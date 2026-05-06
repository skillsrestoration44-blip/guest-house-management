<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GuestDocument extends Model
{
    use HasFactory;

    protected $table = 'guest_documents';

    protected $fillable = ['guest_id', 'document_type', 'document_number', 'issue_date', 'expiry_date', 'file_path', 'created_by'];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function guest(): BelongsTo { return $this->belongsTo(\App\Models\Guest::class); }
    public function creator(): BelongsTo { return $this->belongsTo(\App\Models\User::class, 'created_by'); }
}
