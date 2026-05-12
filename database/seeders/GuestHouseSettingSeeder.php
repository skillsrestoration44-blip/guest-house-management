<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuestHouseSettingSeeder extends Seeder
{
    public function run(): void
    {
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
