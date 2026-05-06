<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StayGuest extends Model
{
    use HasFactory;

    protected $table = 'stay_guests';

    protected $fillable = ['stay_id', 'guest_id', 'is_primary'];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function stay(): BelongsTo { return $this->belongsTo(\App\Models\Stay::class); }
    public function guest(): BelongsTo { return $this->belongsTo(\App\Models\Guest::class); }
}
