<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Payment extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'payments';

    protected $fillable = ['branch_id', 'payment_no', 'invoice_id', 'booking_id', 'stay_id', 'guest_id', 'payment_date', 'payment_method_id', 'payment_type', 'amount', 'reference_no', 'status', 'note', 'received_by'];

    protected $casts = [
        'payment_date' => 'datetime',
    ];

    public function invoice(): BelongsTo { return $this->belongsTo(\App\Models\Invoice::class); }
    public function booking(): BelongsTo { return $this->belongsTo(\App\Models\Booking::class); }
    public function stay(): BelongsTo { return $this->belongsTo(\App\Models\Stay::class); }
    public function guest(): BelongsTo { return $this->belongsTo(\App\Models\Guest::class); }
    public function paymentMethod(): BelongsTo { return $this->belongsTo(\App\Models\PaymentMethod::class); }
    public function receiver(): BelongsTo { return $this->belongsTo(\App\Models\User::class, 'received_by'); }
    public function receipt(): HasOne { return $this->hasOne(\App\Models\Receipt::class); }
}
