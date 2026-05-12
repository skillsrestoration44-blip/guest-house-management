<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'super_admin', 'display_name' => 'Super Admin', 'description' => 'Full access', 'status' => 'active'],
            ['name' => 'admin', 'display_name' => 'Admin', 'description' => 'Branch admin', 'status' => 'active'],
            ['name' => 'receptionist', 'display_name' => 'Receptionist', 'description' => 'Front desk', 'status' => 'active'],
            ['name' => 'housekeeping', 'display_name' => 'Housekeeping', 'description' => 'Housekeeping staff', 'status' => 'active'],
            ['name' => 'accountant', 'display_name' => 'Accountant', 'description' => 'Finance', 'status' => 'active'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
