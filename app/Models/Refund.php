<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Refund extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'refunds';

    protected $fillable = ['branch_id', 'refund_no', 'payment_id', 'invoice_id', 'guest_id', 'amount', 'reason', 'refunded_at', 'refunded_by', 'status'];

    protected $casts = [
        'refunded_at' => 'datetime',
    ];

    public function payment(): BelongsTo { return $this->belongsTo(\App\Models\Payment::class); }
    public function invoice(): BelongsTo { return $this->belongsTo(\App\Models\Invoice::class); }
    public function guest(): BelongsTo { return $this->belongsTo(\App\Models\Guest::class); }
    public function refunder(): BelongsTo { return $this->belongsTo(\App\Models\User::class, 'refunded_by'); }
}
