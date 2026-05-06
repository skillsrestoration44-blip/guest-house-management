<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Supplier extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'suppliers';

    protected $fillable = ['branch_id', 'name', 'phone', 'email', 'address', 'contact_person', 'status'];

    public function stockItems(): HasMany { return $this->hasMany(\App\Models\StockItem::class); }
}
