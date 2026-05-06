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

class Booking extends Model
{
    use HasFactory, SoftDeletes, BelongsToBranch;

    protected $table = 'bookings';

    protected $fillable = ['branch_id', 'booking_no', 'guest_id', 'room_id', 'booking_source', 'check_in_date', 'check_out_date', 'check_in_time', 'check_out_time', 'adults', 'children', 'total_guests', 'room_price', 'deposit_amount', 'discount_amount', 'status', 'note', 'created_by', 'cancelled_by', 'cancelled_at', 'cancel_reason'];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'cancelled_at' => 'datetime',
    ];

    public function guest(): BelongsTo { return $this->belongsTo(\App\Models\Guest::class); }
    public function room(): BelongsTo { return $this->belongsTo(\App\Models\Room::class); }
    public function creator(): BelongsTo { return $this->belongsTo(\App\Models\User::class, 'created_by'); }
    public function canceller(): BelongsTo { return $this->belongsTo(\App\Models\User::class, 'cancelled_by'); }
    public function guests(): BelongsToMany { return $this->belongsToMany(\App\Models\Guest::class, 'booking_guests'); }
    public function statusHistories(): HasMany { return $this->hasMany(\App\Models\BookingStatusHistory::class); }
    public function stay(): HasOne { return $this->hasOne(\App\Models\Stay::class); }
}
