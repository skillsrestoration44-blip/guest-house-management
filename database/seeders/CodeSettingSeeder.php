<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CodeSettingSeeder extends Seeder
{
    public function run(): void
    {
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
            ['code_type' => 'housekeeping', 'prefix' => 'HK', 'next_number' => 1, 'digit_length' => 6, 'example' => 'HK-000001'],
            ['code_type' => 'feedback', 'prefix' => 'FB', 'next_number' => 1, 'digit_length' => 6, 'example' => 'FB-000001'],
            ['code_type' => 'complaint', 'prefix' => 'CMP', 'next_number' => 1, 'digit_length' => 6, 'example' => 'CMP-000001'],
            ['code_type' => 'capa', 'prefix' => 'CAPA', 'next_number' => 1, 'digit_length' => 6, 'example' => 'CAPA-000001'],
            ['code_type' => 'risk', 'prefix' => 'RSK', 'next_number' => 1, 'digit_length' => 6, 'example' => 'RSK-000001'],
        ];

        foreach ($codeSettings as $codeSetting) {
            DB::table('code_settings')->updateOrInsert(
                ['code_type' => $codeSetting['code_type']],
                $codeSetting + ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
