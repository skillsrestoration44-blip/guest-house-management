<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\ResolvesBranch;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\Guest;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Stay;
use Illuminate\View\View;

class DashboardController extends Controller
{
    use ResolvesBranch;

    public function index(): View
    {
        $branchId = $this->currentBranchId();

        $countRooms = $this->applyBranchScope(Room::query())->count();
        $countGuests = $this->applyBranchScope(Guest::query())->count();
        $countBookings = $this->applyBranchScope(Booking::query())->count();
        $countCheckedIn = $this->applyBranchScope(Stay::query())->where('status', 'checked_in')->count();
        $totalInvoices = $this->applyBranchScope(Invoice::query())->sum('grand_total');
        $totalPayments = $this->applyBranchScope(Payment::query())->where('status', 'completed')->sum('amount');

        $branches = Branch::where('status', 'active')->get();

        return view('admin.dashboard.index', compact(
            'countRooms', 'countGuests', 'countBookings', 'countCheckedIn',
            'totalInvoices', 'totalPayments', 'branches', 'branchId'
        ));
    }
}
