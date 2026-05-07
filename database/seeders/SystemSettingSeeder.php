<?php

namespace Database\Seeders;

use App\Models\Backup;
use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['site_name',          'Sample Guest House',  'string', 'general'],
            ['default_currency',   'USD',                 'string', 'general'],
            ['default_locale',     'en',                  'string', 'general'],
            ['supported_locales',  '["en","km"]',         'json',   'general'],
            ['booking_window_days','120',                 'integer','booking'],
            ['deposit_percent',    '30',                  'integer','booking'],
            ['vat_percent',        '0',                   'integer','accounting'],
            ['housekeeping_auto_create', 'true',          'boolean','housekeeping'],
            ['low_stock_threshold','10',                  'integer','inventory'],
            ['session_timeout_min','120',                 'integer','security'],
            ['enable_2fa',         'false',               'boolean','security'],
            ['enable_audit_log',   'true',                'boolean','security'],
        ];

        foreach ($settings as [$key, $val, $type, $group]) {
            SystemSetting::firstOrCreate(
                ['key' => $key],
                ['value' => $val, 'type' => $type, 'setting_group' => $group]
            );
        }

        /* A handful of sample backup entries (file_path is descriptive only) */
        $samples = [
            ['db-2025-04-30.sql.gz', 'storage/backups/db-2025-04-30.sql.gz',  152384, 'auto',   'success'],
            ['db-2025-05-01.sql.gz', 'storage/backups/db-2025-05-01.sql.gz',  158722, 'auto',   'success'],
            ['db-2025-05-02.sql.gz', 'storage/backups/db-2025-05-02.sql.gz',       0, 'auto',   'failed'],
            ['db-2025-05-03.sql.gz', 'storage/backups/db-2025-05-03.sql.gz',  161020, 'manual', 'success'],
        ];

        foreach ($samples as [$name, $path, $size, $type, $status]) {
            Backup::firstOrCreate(
                ['file_name' => $name],
                [
                    'file_path'   => $path,
                    'file_size'   => $size,
                    'backup_type' => $type,
                    'status'      => $status,
                    'created_by'  => 1,
                ]
            );
        }
    }
}
