<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\PaymentMethod;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            ['code' => 'BR-MAIN', 'name' => 'Main Branch', 'phone' => '012345678', 'manager_name' => 'Admin', 'is_default' => true, 'status' => 'active'],
            ['code' => 'BR-BB', 'name' => 'Battambang Branch', 'phone' => '012345679', 'manager_name' => 'Manager', 'is_default' => false, 'status' => 'active'],
            ['code' => 'BR-SR', 'name' => 'Siem Reap Branch', 'phone' => '012345680', 'manager_name' => 'Manager', 'is_default' => false, 'status' => 'active'],
        ];
        foreach ($branches as $b) {
            Branch::firstOrCreate(['code' => $b['code']], $b);
        }

        $roles = [
            ['name' => 'super_admin', 'display_name' => 'Super Admin', 'description' => 'Full access', 'status' => 'active'],
            ['name' => 'admin', 'display_name' => 'Admin', 'description' => 'Branch admin', 'status' => 'active'],
            ['name' => 'receptionist', 'display_name' => 'Receptionist', 'description' => 'Front desk', 'status' => 'active'],
            ['name' => 'housekeeping', 'display_name' => 'Housekeeping', 'description' => 'Housekeeping staff', 'status' => 'active'],
            ['name' => 'accountant', 'display_name' => 'Accountant', 'description' => 'Finance', 'status' => 'active'],
        ];
        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r['name']], $r);
        }

        $modules = ['branches', 'staff', 'users', 'roles', 'rooms', 'guests', 'bookings', 'stays', 'invoices', 'payments', 'services', 'housekeeping', 'maintenance', 'inventory', 'expenses', 'reports', 'settings'];
        $actions = ['view', 'create', 'edit', 'delete'];
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(
                    ['name' => "{$module}.{$action}"],
                    ['module' => $module, 'name' => "{$module}.{$action}", 'display_name' => ucfirst($action) . ' ' . ucwords(str_replace('_', ' ', $module))]
                );
            }
        }

        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->permissions()->sync(Permission::pluck('id'));
        }

        $payments = [
            ['name' => 'Cash', 'code' => 'CASH'],
            ['name' => 'Bank Transfer', 'code' => 'BANK'],
            ['name' => 'Credit Card', 'code' => 'CARD'],
            ['name' => 'ABA Pay', 'code' => 'ABA'],
            ['name' => 'Wing', 'code' => 'WING'],
            ['name' => 'PiPay', 'code' => 'PIPAY'],
            ['name' => 'Acleda', 'code' => 'ACLEDA'],
        ];
        foreach ($payments as $p) {
            PaymentMethod::firstOrCreate(['code' => $p['code']], array_merge($p, ['status' => 'active']));
        }

        $defaultBranch = Branch::where('is_default', true)->first() ?? Branch::first();

        $adminStaff = Staff::firstOrCreate(
            ['staff_code' => 'STF-0001'],
            [
                'branch_id' => $defaultBranch?->id,
                'full_name' => 'System Administrator',
                'phone' => '012000000',
                'email' => 'admin@example.com',
                'position' => 'Admin',
                'salary' => 0,
                'hire_date' => now(),
                'status' => 'active',
            ]
        );

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'branch_id' => $defaultBranch?->id,
                'staff_id' => $adminStaff->id,
                'name' => 'Administrator',
                'username' => 'admin',
                'phone' => '012000000',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        );

        if ($superAdmin) {
            $adminUser->roles()->syncWithoutDetaching([$superAdmin->id]);
        }

        $codeSettings = [
            ['code_type' => 'guest', 'prefix' => 'G', 'next_number' => 1, 'digit_length' => 5, 'example' => 'G-00001'],
            ['code_type' => 'booking', 'prefix' => 'BK', 'next_number' => 1, 'digit_length' => 6, 'example' => 'BK-000001'],
            ['code_type' => 'stay', 'prefix' => 'ST', 'next_number' => 1, 'digit_length' => 6, 'example' => 'ST-000001'],
            ['code_type' => 'invoice', 'prefix' => 'INV', 'next_number' => 1, 'digit_length' => 6, 'example' => 'INV-000001'],
            ['code_type' => 'payment', 'prefix' => 'PAY', 'next_number' => 1, 'digit_length' => 6, 'example' => 'PAY-000001'],
            ['code_type' => 'receipt', 'prefix' => 'RCP', 'next_number' => 1, 'digit_length' => 6, 'example' => 'RCP-000001'],
            ['code_type' => 'refund', 'prefix' => 'REF', 'next_number' => 1, 'digit_length' => 6, 'example' => 'REF-000001'],
            ['code_type' => 'expense', 'prefix' => 'EXP', 'next_number' => 1, 'digit_length' => 6, 'example' => 'EXP-000001'],
            ['code_type' => 'request', 'prefix' => 'REQ', 'next_number' => 1, 'digit_length' => 6, 'example' => 'REQ-000001'],
            ['code_type' => 'task', 'prefix' => 'TSK', 'next_number' => 1, 'digit_length' => 6, 'example' => 'TSK-000001'],
        ];
        foreach ($codeSettings as $cs) {
            DB::table('code_settings')->updateOrInsert(['code_type' => $cs['code_type']], $cs + ['created_at' => now(), 'updated_at' => now()]);
        }

        DB::table('guest_house_settings')->updateOrInsert(['id' => 1], [
            'name' => 'Sample Guest House',
            'phone' => '012345678',
            'email' => 'info@example.com',
            'address' => 'Phnom Penh, Cambodia',
            'currency' => 'USD',
            'timezone' => 'Asia/Phnom_Penh',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
