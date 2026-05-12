<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Top-level seeder orchestrating bootstrap seeders first, then domain seeders.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            'Database\\Seeders\\BranchSeeder',
            'Database\\Seeders\\RoleSeeder',
            'Database\\Seeders\\PermissionSeeder',
            'Database\\Seeders\\RoleDefaultPermissionSeeder',
            'Database\\Seeders\\PaymentMethodSeeder',
            'Database\\Seeders\\DefaultAdminSeeder',
            'Database\\Seeders\\CodeSettingSeeder',
            'Database\\Seeders\\GuestHouseSettingSeeder',
            'Database\\Seeders\\CoreSystemSeeder',
            'Database\\Seeders\\RoomManagementSeeder',
            'Database\\Seeders\\GuestSeeder',
            'Database\\Seeders\\BookingSeeder',
            'Database\\Seeders\\StaySeeder',
            'Database\\Seeders\\ServiceSeeder',
            'Database\\Seeders\\InvoiceAndPaymentSeeder',
            'Database\\Seeders\\HousekeepingSeeder',
            'Database\\Seeders\\MaintenanceSeeder',
            'Database\\Seeders\\InventorySeeder',
            'Database\\Seeders\\AccountingSeeder',
            'Database\\Seeders\\NotificationSeeder',
            'Database\\Seeders\\WebsiteSeeder',
            'Database\\Seeders\\SecuritySeeder',
            'Database\\Seeders\\SystemSettingSeeder',
            'Database\\Seeders\\Iso9001Seeder',
        ]);
    }
}
