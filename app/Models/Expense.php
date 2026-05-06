<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Expense extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'expenses';

    protected $fillable = ['branch_id', 'expense_no', 'expense_category_id', 'expense_date', 'description', 'amount', 'payment_method_id', 'reference_no', 'attachment', 'status', 'created_by', 'approved_by', 'approved_at'];

    protected $casts = [
        'expense_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function category(): BelongsTo { return $this->belongsTo(\App\Models\ExpenseCategory::class, 'expense_category_id'); }
    public function paymentMethod(): BelongsTo { return $this->belongsTo(\App\Models\PaymentMethod::class); }
    public function creator(): BelongsTo { return $this->belongsTo(\App\Models\User::class, 'created_by'); }
    public function approver(): BelongsTo { return $this->belongsTo(\App\Models\User::class, 'approved_by'); }
}
