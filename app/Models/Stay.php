<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Stay extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'stays';

    protected $fillable = ['branch_id', 'stay_no', 'booking_id', 'guest_id', 'room_id', 'actual_check_in_at', 'expected_check_out_at', 'actual_check_out_at', 'check_in_by', 'check_out_by', 'room_price', 'deposit_amount', 'damage_fee', 'late_checkout_fee', 'status', 'note'];

    protected $casts = [
        'actual_check_in_at' => 'datetime',
        'expected_check_out_at' => 'datetime',
        'actual_check_out_at' => 'datetime',
    ];

    public function booking(): BelongsTo { return $this->belongsTo(\App\Models\Booking::class); }
    public function guest(): BelongsTo { return $this->belongsTo(\App\Models\Guest::class); }
    public function room(): BelongsTo { return $this->belongsTo(\App\Models\Room::class); }
    public function checkInBy(): BelongsTo { return $this->belongsTo(\App\Models\User::class, 'check_in_by'); }
    public function checkOutBy(): BelongsTo { return $this->belongsTo(\App\Models\User::class, 'check_out_by'); }
    public function transfers(): HasMany { return $this->hasMany(\App\Models\RoomTransfer::class); }
    public function guests(): BelongsToMany { return $this->belongsToMany(\App\Models\Guest::class, 'stay_guests'); }
}
