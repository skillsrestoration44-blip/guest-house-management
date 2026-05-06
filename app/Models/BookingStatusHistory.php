<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BookingStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'booking_status_histories';

    protected $fillable = ['booking_id', 'old_status', 'new_status', 'reason', 'changed_by'];

    public function booking(): BelongsTo { return $this->belongsTo(\App\Models\Booking::class); }
    public function changer(): BelongsTo { return $this->belongsTo(\App\Models\User::class, 'changed_by'); }
}
