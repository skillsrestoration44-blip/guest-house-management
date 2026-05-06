<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AuditLog extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'audit_logs';

    protected $fillable = ['branch_id', 'user_id', 'action', 'module', 'auditable_type', 'auditable_id', 'old_values', 'new_values', 'ip_address', 'user_agent'];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public $timestamps = false;

    public function user(): BelongsTo { return $this->belongsTo(\App\Models\User::class); }
}
