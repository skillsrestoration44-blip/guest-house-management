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
    public function receipts(): HasMany { return $this->hasMany(\App\Models\Receipt::class); }
    public function refunds(): HasMany { return $this->hasMany(\App\Models\Refund::class); }
    public function creator(): BelongsTo { return $this->belongsTo(\App\Models\User::class, 'created_by'); }

    /**
     * Recalculate `room_total`, `service_total`, `damage_total`, `grand_total`,
     * `paid_amount`, `balance_due`, and `status` from the current children.
     */
    public function recalculate(): void
    {
        $items = $this->items()->get();
        $room      = (float) $items->where('item_type', 'room')->sum('total');
        $service   = (float) $items->where('item_type', 'service')->sum('total');
        $damage    = (float) $items->where('item_type', 'damage')->sum('total');
        $other     = (float) $items->whereNotIn('item_type', ['room','service','damage','discount','tax'])->sum('total');
        $discount  = (float) $items->where('item_type', 'discount')->sum('total');
        $tax       = (float) $items->where('item_type', 'tax')->sum('total');

        $grand = $room + $service + $damage + $other + $tax - abs($discount) - (float) $this->deposit_amount;
        if ($grand < 0) $grand = 0;

        $paid = (float) $this->payments()
            ->where('status', 'completed')
            ->where('payment_type', '!=', 'refund')
            ->sum('amount');
        $refunded = (float) $this->refunds()
            ->where('status', 'completed')
            ->sum('amount');
        $netPaid = max(0, $paid - $refunded);
        $balance = round($grand - $netPaid, 2);

        $status = $this->status;
        if ($status !== 'cancelled' && $status !== 'draft') {
            if ($balance <= 0 && $grand > 0) $status = 'paid';
            elseif ($netPaid > 0 && $balance > 0) $status = 'partial';
            elseif ($netPaid <= 0) $status = 'unpaid';
        }

        $this->forceFill([
            'room_total' => $room,
            'service_total' => $service,
            'damage_total' => $damage,
            'discount_amount' => abs($discount),
            'tax_amount' => $tax,
            'grand_total' => round($grand, 2),
            'paid_amount' => round($netPaid, 2),
            'balance_due' => $balance,
            'status' => $status,
        ])->saveQuietly();
    }
}
