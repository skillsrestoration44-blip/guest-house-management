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

class Guest extends Model
{
    use HasFactory, SoftDeletes, BelongsToBranch;

    protected $table = 'guests';

    protected $fillable = ['branch_id', 'guest_code', 'full_name', 'gender', 'phone', 'email', 'nationality', 'address', 'date_of_birth', 'photo', 'is_blacklisted', 'blacklist_reason', 'note'];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_blacklisted' => 'boolean',
    ];

    public function documents(): HasMany { return $this->hasMany(\App\Models\GuestDocument::class); }
    public function bookings(): HasMany { return $this->hasMany(\App\Models\Booking::class); }
    public function stays(): HasMany { return $this->hasMany(\App\Models\Stay::class); }
}
