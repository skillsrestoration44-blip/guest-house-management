<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\BranchSwitchController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PublicSiteController;
use Illuminate\Support\Facades\Route;

Route::post('/locale/switch', [LocaleController::class, 'switch']);
Route::get('/locale/switch', function (\Illuminate\Http\Request $r) {
    $locale = in_array($r->query('locale'), ['en', 'km'], true) ? $r->query('locale') : 'en';
    session()->put('locale', $locale);
    return back()->withCookie(cookie()->forever('locale', $locale));
});

Route::post('/branch/switch', [BranchSwitchController::class, 'switch']);

/*
|--------------------------------------------------------------------------
| Public API (consumed by the Vue.js front layout)
|--------------------------------------------------------------------------
*/
Route::prefix('api/public')->group(function () {
    Route::get('rooms', [PublicSiteController::class, 'rooms']);
    Route::get('rooms/{id}', [PublicSiteController::class, 'room'])->whereNumber('id');
    Route::get('branches', [PublicSiteController::class, 'branches']);
    Route::get('room-types', [PublicSiteController::class, 'roomTypes']);
    Route::get('services', [PublicSiteController::class, 'services']);
    Route::get('pages/{slug}', [PublicSiteController::class, 'page']);
    Route::post('online-booking', [PublicSiteController::class, 'submitBooking']);
    Route::post('online-booking/status', [PublicSiteController::class, 'lookupBooking']);
    Route::post('contact', [PublicSiteController::class, 'contact']);
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    /* Auth */
    Route::middleware('guest')->group(function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [LoginController::class, 'login'])->name('login.attempt');
    });

    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    /* Authenticated */
    Route::middleware('admin.auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.alt');

        $resources = [
            'branches' => \App\Http\Controllers\Admin\BranchController::class,
            'staff' => \App\Http\Controllers\Admin\StaffController::class,
            'users' => \App\Http\Controllers\Admin\UserController::class,
            'roles' => \App\Http\Controllers\Admin\RoleController::class,
            'permissions' => \App\Http\Controllers\Admin\PermissionController::class,
            'staff_attendances' => \App\Http\Controllers\Admin\StaffAttendanceController::class,
            'room_types' => \App\Http\Controllers\Admin\RoomTypeController::class,
            'rooms' => \App\Http\Controllers\Admin\RoomController::class,
            'facilities' => \App\Http\Controllers\Admin\FacilityController::class,
            'guests' => \App\Http\Controllers\Admin\GuestController::class,
            'guest_documents' => \App\Http\Controllers\Admin\GuestDocumentController::class,
            'bookings' => \App\Http\Controllers\Admin\BookingController::class,
            'stays' => \App\Http\Controllers\Admin\StayController::class,
            'room_transfers' => \App\Http\Controllers\Admin\RoomTransferController::class,
            'payment_methods' => \App\Http\Controllers\Admin\PaymentMethodController::class,
            'invoices' => \App\Http\Controllers\Admin\InvoiceController::class,
            'payments' => \App\Http\Controllers\Admin\PaymentController::class,
            'receipts' => \App\Http\Controllers\Admin\ReceiptController::class,
            'refunds' => \App\Http\Controllers\Admin\RefundController::class,
            'services' => \App\Http\Controllers\Admin\ServiceController::class,
            'service_charges' => \App\Http\Controllers\Admin\ServiceChargeController::class,
            'housekeeping_tasks' => \App\Http\Controllers\Admin\HousekeepingTaskController::class,
            'housekeeping_checklist_items' => \App\Http\Controllers\Admin\HousekeepingChecklistItemController::class,
            'maintenance_requests' => \App\Http\Controllers\Admin\MaintenanceRequestController::class,
            'suppliers' => \App\Http\Controllers\Admin\SupplierController::class,
            'stock_categories' => \App\Http\Controllers\Admin\StockCategoryController::class,
            'stock_items' => \App\Http\Controllers\Admin\StockItemController::class,
            'stock_movements' => \App\Http\Controllers\Admin\StockMovementController::class,
            'expense_categories' => \App\Http\Controllers\Admin\ExpenseCategoryController::class,
            'expenses' => \App\Http\Controllers\Admin\ExpenseController::class,
            'salaries' => \App\Http\Controllers\Admin\SalaryController::class,
            'notifications' => \App\Http\Controllers\Admin\NotificationController::class,
            'notification_templates' => \App\Http\Controllers\Admin\NotificationTemplateController::class,
            'website_pages' => \App\Http\Controllers\Admin\WebsitePageController::class,
            'online_booking_requests' => \App\Http\Controllers\Admin\OnlineBookingRequestController::class,
            'login_histories' => \App\Http\Controllers\Admin\LoginHistoryController::class,
            'audit_logs' => \App\Http\Controllers\Admin\AuditLogController::class,
            'guest_house_settings' => \App\Http\Controllers\Admin\GuestHouseSettingController::class,
            'code_settings' => \App\Http\Controllers\Admin\CodeSettingController::class,
            'system_settings' => \App\Http\Controllers\Admin\SystemSettingController::class,
            'backups' => \App\Http\Controllers\Admin\BackupController::class,

            /* ISO 9001 quality-management modules */
            'guest-feedbacks' => \App\Http\Controllers\Admin\GuestFeedbackController::class,
            'complaints' => \App\Http\Controllers\Admin\ComplaintController::class,
            'risks' => \App\Http\Controllers\Admin\RiskController::class,
            'supplier-scorecards' => \App\Http\Controllers\Admin\SupplierScorecardController::class,
            'corrective-actions' => \App\Http\Controllers\Admin\CorrectiveActionController::class,
        ];

        foreach ($resources as $slug => $controller) {
            Route::resource($slug, $controller);
        }
    });
});

/*
|--------------------------------------------------------------------------
| Public Front Layout (Vue.js 3 SPA)
|--------------------------------------------------------------------------
| Serves the Vue SPA for `/` and any non-admin, non-api route. Vue Router
| owns client-side navigation for these paths (home, rooms, booking, etc.).
*/
Route::get('/', fn () => view('frontend'))->name('frontend.home');

Route::get('/{any}', fn () => view('frontend'))
    ->where('any', '^(?!admin|api|locale|branch|build|storage|_debugbar|vendor).*$')
    ->name('frontend.spa');
