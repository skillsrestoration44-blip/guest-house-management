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

class Room extends Model
{
    use HasFactory, SoftDeletes, BelongsToBranch;

    protected $table = 'rooms';

    protected $fillable = ['branch_id', 'room_type_id', 'room_number', 'floor', 'bed_count', 'max_guests', 'price_per_night', 'price_per_hour', 'status', 'description'];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'price_per_hour' => 'decimal:2',
    ];

    public function roomType(): BelongsTo { return $this->belongsTo(\App\Models\RoomType::class); }
    public function images(): HasMany { return $this->hasMany(\App\Models\RoomImage::class); }
    public function facilities(): BelongsToMany { return $this->belongsToMany(\App\Models\Facility::class, 'facility_room'); }
    public function bookings(): HasMany { return $this->hasMany(\App\Models\Booking::class); }
    public function stays(): HasMany { return $this->hasMany(\App\Models\Stay::class); }
}
