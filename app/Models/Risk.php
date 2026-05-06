<?php

namespace App\Models;

use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Risk extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'risks';

    protected $fillable = [
        'branch_id', 'risk_no', 'title', 'description', 'category',
        'likelihood', 'impact', 'risk_score', 'mitigation_plan',
        'owner_id', 'review_date', 'status',
    ];

    protected $casts = ['review_date' => 'date'];

    protected static function booted(): void
    {
        static::saving(function (self $risk) {
            $risk->risk_score = ((int) $risk->likelihood) * ((int) $risk->impact);
        });
    }

    public function owner(): BelongsTo { return $this->belongsTo(User::class, 'owner_id'); }
}
