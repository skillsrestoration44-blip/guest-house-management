<?php

namespace App\Providers;

use App\Models;
use App\Observers\AuditObserver;
use App\Observers\BookingObserver;
use App\Observers\InvoiceItemObserver;
use App\Observers\PaymentObserver;
use App\Observers\StayObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Schema::defaultStringLength(191);

        /* Audit observer for every domain model */
        $this->registerAuditObservers();

        /* Workflow observers for cross-module automation */
        Models\Booking::observe(BookingObserver::class);
        Models\Payment::observe(PaymentObserver::class);
        Models\Stay::observe(StayObserver::class);
        Models\InvoiceItem::observe(InvoiceItemObserver::class);

        /* Custom Blade directive: @permission('bookings.create') ... @endpermission */
        Blade::if('permission', function (string $perm) {
            $u = Auth::user();
            return $u && method_exists($u, 'hasPermission') && $u->hasPermission($perm);
        });
    }

    protected function registerAuditObservers(): void
    {
        $auditable = [
            Models\Branch::class, Models\Staff::class, Models\User::class,
            Models\Role::class, Models\Permission::class, Models\StaffAttendance::class,
            Models\RoomType::class, Models\Room::class, Models\Facility::class,
            Models\Guest::class, Models\GuestDocument::class, Models\Booking::class,
            Models\Stay::class, Models\RoomTransfer::class, Models\PaymentMethod::class,
            Models\Invoice::class, Models\InvoiceItem::class, Models\Payment::class,
            Models\Receipt::class, Models\Refund::class, Models\Service::class,
            Models\ServiceCharge::class, Models\HousekeepingTask::class,
            Models\HousekeepingChecklistItem::class, Models\HousekeepingTaskCheck::class,
            Models\MaintenanceRequest::class, Models\MaintenanceCost::class,
            Models\MaintenancePhoto::class, Models\Supplier::class,
            Models\StockCategory::class, Models\StockItem::class,
            Models\StockMovement::class, Models\ExpenseCategory::class,
            Models\Expense::class, Models\Salary::class,
            Models\NotificationTemplate::class, Models\WebsitePage::class,
            Models\OnlineBookingRequest::class, Models\GuestHouseSetting::class,
            Models\CodeSetting::class, Models\SystemSetting::class,
        ];
        foreach ($auditable as $modelClass) {
            $modelClass::observe(AuditObserver::class);
        }
    }
}
