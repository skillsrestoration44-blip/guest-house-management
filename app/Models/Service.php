<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = ['name', 'category', 'unit', 'price', 'description', 'status'];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function charges(): HasMany { return $this->hasMany(\App\Models\ServiceCharge::class); }
}
