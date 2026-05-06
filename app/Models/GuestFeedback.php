<?php

namespace App\Models;

use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuestFeedback extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'guest_feedbacks';

    protected $fillable = [
        'branch_id', 'feedback_no', 'guest_id', 'stay_id', 'booking_id',
        'rating', 'cleanliness_score', 'service_score', 'value_score',
        'comment', 'tags', 'status', 'reviewed_by', 'reviewed_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function guest(): BelongsTo { return $this->belongsTo(Guest::class); }
    public function stay(): BelongsTo { return $this->belongsTo(Stay::class); }
    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class, 'reviewed_by'); }
}
