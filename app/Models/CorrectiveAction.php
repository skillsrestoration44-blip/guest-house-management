<?php

namespace App\Models;

use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CorrectiveAction extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'corrective_actions';

    protected $fillable = [
        'branch_id', 'capa_no', 'type', 'source_type', 'source_id',
        'title', 'description', 'root_cause', 'action_taken', 'verification',
        'target_date', 'completed_date', 'status', 'owner_id',
    ];

    protected $casts = [
        'target_date' => 'date',
        'completed_date' => 'date',
    ];

    public function source(): MorphTo { return $this->morphTo('source'); }
    public function owner(): BelongsTo { return $this->belongsTo(User::class, 'owner_id'); }
}
