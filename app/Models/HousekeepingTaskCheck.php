<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class HousekeepingTaskCheck extends Model
{
    use HasFactory;

    protected $table = 'housekeeping_task_checks';

    protected $fillable = ['housekeeping_task_id', 'checklist_item_id', 'is_checked', 'note', 'photo_path', 'checked_by', 'checked_at'];

    protected $casts = [
        'is_checked' => 'boolean',
        'checked_at' => 'datetime',
    ];

    public function task(): BelongsTo { return $this->belongsTo(\App\Models\HousekeepingTask::class, 'housekeeping_task_id'); }
    public function item(): BelongsTo { return $this->belongsTo(\App\Models\HousekeepingChecklistItem::class, 'checklist_item_id'); }
    public function checker(): BelongsTo { return $this->belongsTo(\App\Models\User::class, 'checked_by'); }
}
