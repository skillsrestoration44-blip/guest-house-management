<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Role;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultAdminSeeder extends Seeder
{
    public function run(): void
    {
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

        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $adminUser->roles()->syncWithoutDetaching([$superAdmin->id]);
        }
    }
}
