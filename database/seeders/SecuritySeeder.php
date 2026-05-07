<?php

namespace Database\Seeders;

use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Database\Seeder;

class SecuritySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/126.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Safari/605.1',
            'Mozilla/5.0 (X11; Linux x86_64) Firefox/127.0',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_5) Safari/604.1',
        ];

        foreach ($users as $idx => $user) {
            for ($i = 0; $i < 5; $i++) {
                $loginAt = now()->subDays($i + 1)->setTime(8 + $i, random_int(0, 59));
                LoginHistory::firstOrCreate(
                    [
                        'user_id'  => $user->id,
                        'login_at' => $loginAt,
                    ],
                    [
                        'branch_id'      => $user->branch_id,
                        'ip_address'     => '203.115.92.' . random_int(2, 254),
                        'user_agent'     => $userAgents[($idx + $i) % count($userAgents)],
                        'logout_at'      => $loginAt->copy()->addHours(2),
                        'status'         => 'success',
                        'failure_reason' => null,
                    ]
                );
            }

            /* One failed attempt to demonstrate audit visibility */
            LoginHistory::firstOrCreate(
                ['user_id' => $user->id, 'login_at' => now()->subDays(2)->setTime(7, 30)],
                [
                    'branch_id'      => $user->branch_id,
                    'ip_address'     => '203.115.92.99',
                    'user_agent'     => $userAgents[0],
                    'logout_at'      => null,
                    'status'         => 'failed',
                    'failure_reason' => 'Invalid password',
                ]
            );
        }
    }
}
