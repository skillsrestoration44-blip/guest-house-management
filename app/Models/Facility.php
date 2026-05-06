<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Facility extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'facilities';

    protected $fillable = ['branch_id', 'name', 'description', 'status'];

    public function rooms(): BelongsToMany { return $this->belongsToMany(\App\Models\Room::class, 'facility_room'); }
}
