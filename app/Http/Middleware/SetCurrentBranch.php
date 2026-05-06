<?php

namespace App\Http\Middleware;

use App\Models\Branch;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentBranch
{
    /**
     * Resolve the active branch from the session and share it with views.
     *
     * `branch_id = 0` (or null) represents "All Branches".
     */
    public function handle(Request $request, Closure $next): Response
    {
        $session = $request->session();
        $branchId = $session->get('current_branch_id');

        $branch = null;
        if ($branchId) {
            $branch = Branch::where('status', 'active')->find($branchId);
            if (! $branch) {
                $session->forget('current_branch_id');
                $branchId = null;
            }
        }

        $branches = Branch::where('status', 'active')->orderBy('name')->get();

        View::share('currentBranchId', $branchId);
        View::share('currentBranch', $branch);
        View::share('availableBranches', $branches);

        return $next($request);
    }
}
