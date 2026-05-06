<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierScorecard extends Model
{
    use HasFactory;

    protected $table = 'supplier_scorecards';

    protected $fillable = [
        'supplier_id', 'period_start', 'period_end',
        'quality_score', 'delivery_score', 'price_score', 'communication_score',
        'overall_score', 'comments', 'evaluated_by',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $s) {
            $s->overall_score = round(((int) $s->quality_score + (int) $s->delivery_score
                + (int) $s->price_score + (int) $s->communication_score) / 4, 2);
        });
    }

    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
    public function evaluator(): BelongsTo { return $this->belongsTo(User::class, 'evaluated_by'); }
}
