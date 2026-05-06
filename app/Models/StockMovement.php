<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StockMovement extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'stock_movements';

    protected $fillable = ['branch_id', 'stock_item_id', 'movement_type', 'reference_type', 'reference_id', 'quantity', 'unit_cost', 'total_cost', 'note', 'movement_at', 'created_by'];

    protected $casts = [
        'movement_at' => 'datetime',
    ];

    public function item(): BelongsTo { return $this->belongsTo(\App\Models\StockItem::class, 'stock_item_id'); }
    public function creator(): BelongsTo { return $this->belongsTo(\App\Models\User::class, 'created_by'); }
}
