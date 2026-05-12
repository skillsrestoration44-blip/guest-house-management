<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $payments = [
            ['name' => 'Cash', 'code' => 'CASH'],
            ['name' => 'Bank Transfer', 'code' => 'BANK'],
            ['name' => 'Credit Card', 'code' => 'CARD'],
            ['name' => 'ABA Pay', 'code' => 'ABA'],
            ['name' => 'Wing', 'code' => 'WING'],
            ['name' => 'PiPay', 'code' => 'PIPAY'],
            ['name' => 'Acleda', 'code' => 'ACLEDA'],
        ];

        foreach ($payments as $payment) {
            PaymentMethod::firstOrCreate(['code' => $payment['code']], array_merge($payment, ['status' => 'active']));
        }
    }
}
