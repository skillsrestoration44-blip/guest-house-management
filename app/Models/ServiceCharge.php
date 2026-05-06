<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ServiceCharge extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'service_charges';

    protected $fillable = ['branch_id', 'stay_id', 'booking_id', 'guest_id', 'room_id', 'service_id', 'charge_date', 'quantity', 'unit_price', 'total', 'status', 'note', 'created_by'];

    protected $casts = [
        'charge_date' => 'date',
    ];

    public function service(): BelongsTo { return $this->belongsTo(\App\Models\Service::class); }
    public function stay(): BelongsTo { return $this->belongsTo(\App\Models\Stay::class); }
    public function booking(): BelongsTo { return $this->belongsTo(\App\Models\Booking::class); }
    public function guest(): BelongsTo { return $this->belongsTo(\App\Models\Guest::class); }
    public function room(): BelongsTo { return $this->belongsTo(\App\Models\Room::class); }
    public function creator(): BelongsTo { return $this->belongsTo(\App\Models\User::class, 'created_by'); }
}
