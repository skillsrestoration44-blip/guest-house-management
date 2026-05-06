<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BookingGuest extends Model
{
    use HasFactory;

    protected $table = 'booking_guests';

    protected $fillable = ['booking_id', 'guest_id', 'is_primary'];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function booking(): BelongsTo { return $this->belongsTo(\App\Models\Booking::class); }
    public function guest(): BelongsTo { return $this->belongsTo(\App\Models\Guest::class); }
}
