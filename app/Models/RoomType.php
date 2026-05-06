<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RoomType extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'room_types';

    protected $fillable = ['branch_id', 'name', 'description', 'default_price_per_night', 'default_price_per_hour', 'max_guests', 'bed_count', 'status'];

    protected $casts = [
        'default_price_per_night' => 'decimal:2',
        'default_price_per_hour' => 'decimal:2',
    ];

    public function rooms(): HasMany { return $this->hasMany(\App\Models\Room::class); }
}
