<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (self::modules() as $module) {
            foreach (self::actions() as $action) {
                Permission::firstOrCreate(
                    ['name' => "{$module}.{$action}"],
                    [
                        'module' => $module,
                        'name' => "{$module}.{$action}",
                        'display_name' => ucfirst($action) . ' ' . ucwords(str_replace('_', ' ', $module)),
                    ]
                );
            }
        }
    }

    public static function actions(): array
    {
        return ['view', 'create', 'edit', 'delete'];
    }

    public static function modules(): array
    {
        return [
            'branches',
            'staff',
            'users',
            'roles',
            'permissions',
            'staff_attendances',
            'rooms',
            'room_types',
            'facilities',
            'guests',
            'guest_documents',
            'bookings',
            'stays',
            'room_transfers',
            'invoices',
            'payments',
            'payment_methods',
            'receipts',
            'refunds',
            'services',
            'service_charges',
            'housekeeping',
            'housekeeping_tasks',
            'housekeeping_checklist_items',
            'maintenance',
            'maintenance_requests',
            'inventory',
            'suppliers',
            'stock_categories',
            'stock_items',
            'stock_movements',
            'expenses',
            'expense_categories',
            'salaries',
            'reports',
            'settings',
            'notifications',
            'notification_templates',
            'website_pages',
            'online_booking_requests',
            'login_histories',
            'audit_logs',
            'guest_house_settings',
            'code_settings',
            'system_settings',
            'backups',
            'guest_feedbacks',
            'complaints',
            'risks',
            'supplier_scorecards',
            'corrective_actions',
        ];
    }
}
