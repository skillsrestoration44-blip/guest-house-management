<?php

namespace App\Models\Concerns;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToBranch
{
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Scope a query to the currently active branch (if any) stored in the session.
     */
    public function scopeForCurrentBranch(Builder $query): Builder
    {
        $branchId = session('current_branch_id');
        if ($branchId) {
            return $query->where($query->getModel()->getTable().'.branch_id', $branchId);
        }

        return $query;
    }
}
