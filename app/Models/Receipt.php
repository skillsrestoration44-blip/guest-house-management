<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Receipt extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'receipts';

    protected $fillable = ['branch_id', 'receipt_no', 'payment_id', 'invoice_id', 'issued_at', 'issued_by', 'pdf_path'];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    public function payment(): BelongsTo { return $this->belongsTo(\App\Models\Payment::class); }
    public function invoice(): BelongsTo { return $this->belongsTo(\App\Models\Invoice::class); }
    public function issuer(): BelongsTo { return $this->belongsTo(\App\Models\User::class, 'issued_by'); }
}
