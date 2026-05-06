<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class HousekeepingChecklistItem extends Model
{
    use HasFactory;

    protected $table = 'housekeeping_checklist_items';

    protected $fillable = ['name', 'description', 'status'];

    public function taskChecks(): HasMany { return $this->hasMany(\App\Models\HousekeepingTaskCheck::class, 'checklist_item_id'); }
}
