<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RoomTransfer extends Model
{
    use HasFactory;

    protected $table = 'room_transfers';

    protected $fillable = ['stay_id', 'from_room_id', 'to_room_id', 'transfer_at', 'price_difference', 'reason', 'transferred_by'];

    protected $casts = [
        'transfer_at' => 'datetime',
    ];

    public function stay(): BelongsTo { return $this->belongsTo(\App\Models\Stay::class); }
    public function fromRoom(): BelongsTo { return $this->belongsTo(\App\Models\Room::class, 'from_room_id'); }
    public function toRoom(): BelongsTo { return $this->belongsTo(\App\Models\Room::class, 'to_room_id'); }
    public function transferrer(): BelongsTo { return $this->belongsTo(\App\Models\User::class, 'transferred_by'); }
}
