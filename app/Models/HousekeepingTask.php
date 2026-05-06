<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class HousekeepingTask extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'housekeeping_tasks';

    protected $fillable = ['branch_id', 'task_no', 'room_id', 'assigned_to', 'scheduled_at', 'started_at', 'completed_at', 'status', 'note', 'created_by'];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function room(): BelongsTo { return $this->belongsTo(\App\Models\Room::class); }
    public function assignee(): BelongsTo { return $this->belongsTo(\App\Models\Staff::class, 'assigned_to'); }
    public function creator(): BelongsTo { return $this->belongsTo(\App\Models\User::class, 'created_by'); }
    public function checks(): HasMany { return $this->hasMany(\App\Models\HousekeepingTaskCheck::class, 'housekeeping_task_id'); }
}
