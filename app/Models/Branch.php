<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'branches';

    protected $fillable = [
        'code', 'name', 'phone', 'email', 'address',
        'manager_name', 'is_default', 'status',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function staff(): HasMany { return $this->hasMany(Staff::class); }
    public function users(): HasMany { return $this->hasMany(User::class); }
    public function rooms(): HasMany { return $this->hasMany(Room::class); }
    public function bookings(): HasMany { return $this->hasMany(Booking::class); }
}
