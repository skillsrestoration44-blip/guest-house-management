<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MaintenanceCost extends Model
{
    use HasFactory;

    protected $table = 'maintenance_costs';

    protected $fillable = ['maintenance_request_id', 'cost_type', 'description', 'amount', 'created_by'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function request(): BelongsTo { return $this->belongsTo(\App\Models\MaintenanceRequest::class, 'maintenance_request_id'); }
    public function creator(): BelongsTo { return $this->belongsTo(\App\Models\User::class, 'created_by'); }
}
