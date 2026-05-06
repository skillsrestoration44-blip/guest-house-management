<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StockItem extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'stock_items';

    protected $fillable = ['branch_id', 'stock_category_id', 'supplier_id', 'name', 'sku', 'unit', 'purchase_price', 'selling_price', 'current_stock', 'minimum_stock', 'expiry_date', 'status'];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'expiry_date' => 'date',
    ];

    public function category(): BelongsTo { return $this->belongsTo(\App\Models\StockCategory::class, 'stock_category_id'); }
    public function supplier(): BelongsTo { return $this->belongsTo(\App\Models\Supplier::class); }
    public function movements(): HasMany { return $this->hasMany(\App\Models\StockMovement::class); }
}
