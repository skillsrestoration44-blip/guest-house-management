<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleDefaultPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->permissions()->sync(Permission::pluck('id'));
        }

        $permissionIdsByName = Permission::pluck('id', 'name');
        $allAdminModules = array_values(array_diff(PermissionSeeder::modules(), [
            'roles',
            'permissions',
            'login_histories',
            'audit_logs',
        ]));

        $rolePermissions = [
            'admin' => $this->expandPermissionMap(array_fill_keys($allAdminModules, PermissionSeeder::actions())),
            'receptionist' => $this->expandPermissionMap([
                'branches' => ['view'],
                'rooms' => ['view'],
                'room_types' => ['view'],
                'facilities' => ['view'],
                'guests' => PermissionSeeder::actions(),
                'guest_documents' => PermissionSeeder::actions(),
                'bookings' => PermissionSeeder::actions(),
                'stays' => PermissionSeeder::actions(),
                'room_transfers' => ['view', 'create', 'edit'],
                'invoices' => PermissionSeeder::actions(),
                'payments' => PermissionSeeder::actions(),
                'payment_methods' => ['view'],
                'receipts' => ['view', 'create'],
                'refunds' => ['view', 'create'],
                'services' => ['view'],
                'service_charges' => ['view', 'create', 'edit', 'delete'],
                'notifications' => ['view'],
                'online_booking_requests' => PermissionSeeder::actions(),
            ]),
            'housekeeping' => $this->expandPermissionMap([
                'branches' => ['view'],
                'rooms' => ['view'],
                'room_types' => ['view'],
                'facilities' => ['view'],
                'guests' => ['view'],
                'bookings' => ['view'],
                'stays' => ['view', 'edit'],
                'housekeeping' => PermissionSeeder::actions(),
                'housekeeping_tasks' => PermissionSeeder::actions(),
                'housekeeping_checklist_items' => PermissionSeeder::actions(),
                'maintenance' => ['view', 'create', 'edit'],
                'maintenance_requests' => ['view', 'create', 'edit'],
                'notifications' => ['view'],
            ]),
            'accountant' => $this->expandPermissionMap([
                'branches' => ['view'],
                'guests' => ['view'],
                'bookings' => ['view'],
                'stays' => ['view'],
                'invoices' => PermissionSeeder::actions(),
                'payments' => PermissionSeeder::actions(),
                'payment_methods' => PermissionSeeder::actions(),
                'receipts' => PermissionSeeder::actions(),
                'refunds' => PermissionSeeder::actions(),
                'expenses' => PermissionSeeder::actions(),
                'expense_categories' => PermissionSeeder::actions(),
                'salaries' => PermissionSeeder::actions(),
                'stock_movements' => ['view'],
                'reports' => ['view'],
                'notifications' => ['view'],
            ]),
        ];

        foreach ($rolePermissions as $roleName => $permissionNames) {
            $role = Role::where('name', $roleName)->first();

            if (!$role) {
                continue;
            }

            $role->permissions()->sync(
                $permissionIdsByName->only($permissionNames)->values()->all()
            );
        }
    }

    protected function expandPermissionMap(array $modulesToActions): array
    {
        $permissionNames = [];

        foreach ($modulesToActions as $module => $actions) {
            foreach ($actions as $action) {
                $permissionNames[] = "{$module}.{$action}";
            }
        }

        return $permissionNames;
    }
}
