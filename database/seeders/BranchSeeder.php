<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            ['code' => 'BR-MAIN', 'name' => 'Main Branch', 'phone' => '012345678', 'manager_name' => 'Admin', 'is_default' => true, 'status' => 'active'],
            ['code' => 'BR-BB', 'name' => 'Battambang Branch', 'phone' => '012345679', 'manager_name' => 'Manager', 'is_default' => false, 'status' => 'active'],
            ['code' => 'BR-SR', 'name' => 'Siem Reap Branch', 'phone' => '012345680', 'manager_name' => 'Manager', 'is_default' => false, 'status' => 'active'],
        ];

        foreach ($branches as $branch) {
            Branch::firstOrCreate(['code' => $branch['code']], $branch);
        }
    }
}
