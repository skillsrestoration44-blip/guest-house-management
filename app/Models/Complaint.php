<?php

namespace App\Models;

use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'complaints';

    protected $fillable = [
        'branch_id', 'complaint_no', 'guest_id', 'stay_id', 'subject',
        'description', 'severity', 'status', 'assigned_to', 'resolution',
        'resolved_at', 'resolved_by', 'reported_by',
    ];

    protected $casts = ['resolved_at' => 'datetime'];

    public function guest(): BelongsTo { return $this->belongsTo(Guest::class); }
    public function stay(): BelongsTo { return $this->belongsTo(Stay::class); }
    public function assignee(): BelongsTo { return $this->belongsTo(User::class, 'assigned_to'); }
    public function resolver(): BelongsTo { return $this->belongsTo(User::class, 'resolved_by'); }
    public function reporter(): BelongsTo { return $this->belongsTo(User::class, 'reported_by'); }
}
