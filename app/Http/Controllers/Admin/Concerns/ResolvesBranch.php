<?php

namespace App\Http\Controllers\Admin\Concerns;

trait ResolvesBranch
{
    /**
     * Resolve the active branch id from the session, or fall back to the
     * authenticated user's branch.
     */
    protected function currentBranchId(): ?int
    {
        $branchId = session('current_branch_id');
        if ($branchId) {
            return (int) $branchId;
        }

        return auth()->user()->branch_id ?? null;
    }

    protected function applyBranchScope($query, string $column = 'branch_id')
    {
        $branchId = session('current_branch_id');
        if ($branchId) {
            return $query->where($column, $branchId);
        }

        return $query;
    }
}
