<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Staff extends Model
{
    use HasFactory, SoftDeletes, BelongsToBranch;

    protected $table = 'staff';

    protected $fillable = ['branch_id', 'staff_code', 'full_name', 'gender', 'phone', 'email', 'address', 'position', 'salary', 'hire_date', 'photo', 'status'];

    protected $casts = [
        'hire_date' => 'date',
        'salary' => 'decimal:2',
    ];

    public function attendances(): HasMany { return $this->hasMany(\App\Models\StaffAttendance::class); }
    public function salaries(): HasMany { return $this->hasMany(\App\Models\Salary::class); }
    public function user(): HasOne { return $this->hasOne(\App\Models\User::class); }
}
