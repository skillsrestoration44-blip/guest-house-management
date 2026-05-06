<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MaintenanceRequest extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'maintenance_requests';

    protected $fillable = ['branch_id', 'request_no', 'room_id', 'reported_by', 'assigned_to', 'issue_type', 'description', 'priority', 'status', 'reported_at', 'started_at', 'completed_at', 'note'];

    protected $casts = [
        'reported_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function room(): BelongsTo { return $this->belongsTo(\App\Models\Room::class); }
    public function reporter(): BelongsTo { return $this->belongsTo(\App\Models\User::class, 'reported_by'); }
    public function assignee(): BelongsTo { return $this->belongsTo(\App\Models\Staff::class, 'assigned_to'); }
    public function photos(): HasMany { return $this->hasMany(\App\Models\MaintenancePhoto::class); }
    public function costs(): HasMany { return $this->hasMany(\App\Models\MaintenanceCost::class); }
}
