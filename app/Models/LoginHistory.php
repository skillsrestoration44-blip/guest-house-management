<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LoginHistory extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'login_histories';

    protected $fillable = ['branch_id', 'user_id', 'ip_address', 'user_agent', 'login_at', 'logout_at', 'status', 'failure_reason'];

    protected $casts = [
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
    ];

    public function user(): BelongsTo { return $this->belongsTo(\App\Models\User::class); }
}
