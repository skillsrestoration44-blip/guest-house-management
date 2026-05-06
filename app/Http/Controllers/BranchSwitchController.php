<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BranchSwitchController extends Controller
{
    /**
     * AJAX endpoint to switch the active branch context.
     *
     * `branch_id = 0` clears the filter ("All branches").
     */
    public function switch(Request $request): JsonResponse
    {
        $request->validate([
            'branch_id' => ['nullable', 'integer'],
        ]);

        $branchId = (int) $request->input('branch_id');

        if ($branchId > 0) {
            $branch = Branch::where('status', 'active')->findOrFail($branchId);
            $request->session()->put('current_branch_id', $branch->id);

            return response()->json([
                'status' => 'success',
                'branch' => [
                    'id' => $branch->id,
                    'name' => $branch->name,
                    'code' => $branch->code,
                ],
            ]);
        }

        $request->session()->forget('current_branch_id');

        return response()->json([
            'status' => 'success',
            'branch' => null,
        ]);
    }
}
