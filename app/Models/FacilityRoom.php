<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FacilityRoom extends Model
{
    use HasFactory;

    protected $table = 'facility_room';

    protected $fillable = ['room_id', 'facility_id', 'quantity', 'item_condition', 'note'];

    public $timestamps = false;

    public function room(): BelongsTo { return $this->belongsTo(\App\Models\Room::class); }
    public function facility(): BelongsTo { return $this->belongsTo(\App\Models\Facility::class); }
}
