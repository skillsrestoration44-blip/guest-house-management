<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MaintenancePhoto extends Model
{
    use HasFactory;

    protected $table = 'maintenance_photos';

    protected $fillable = ['maintenance_request_id', 'photo_path', 'type'];

    public function request(): BelongsTo { return $this->belongsTo(\App\Models\MaintenanceRequest::class, 'maintenance_request_id'); }
}
