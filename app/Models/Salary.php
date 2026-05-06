<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Salary extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'salaries';

    protected $fillable = ['branch_id', 'staff_id', 'salary_month', 'basic_salary', 'bonus', 'deduction', 'net_salary', 'paid_at', 'payment_method_id', 'status', 'created_by'];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function staff(): BelongsTo { return $this->belongsTo(\App\Models\Staff::class); }
    public function paymentMethod(): BelongsTo { return $this->belongsTo(\App\Models\PaymentMethod::class); }
    public function creator(): BelongsTo { return $this->belongsTo(\App\Models\User::class, 'created_by'); }
}
