<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Invoice extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'invoices';

    protected $fillable = ['branch_id', 'invoice_no', 'booking_id', 'stay_id', 'guest_id', 'invoice_date', 'due_date', 'room_total', 'service_total', 'damage_total', 'discount_amount', 'tax_amount', 'deposit_amount', 'grand_total', 'paid_amount', 'balance_due', 'status', 'created_by'];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
    ];

    public function booking(): BelongsTo { return $this->belongsTo(\App\Models\Booking::class); }
    public function stay(): BelongsTo { return $this->belongsTo(\App\Models\Stay::class); }
    public function guest(): BelongsTo { return $this->belongsTo(\App\Models\Guest::class); }
    public function items(): HasMany { return $this->hasMany(\App\Models\InvoiceItem::class); }
    public function payments(): HasMany { return $this->hasMany(\App\Models\Payment::class); }
    public function creator(): BelongsTo { return $this->belongsTo(\App\Models\User::class, 'created_by'); }
}
