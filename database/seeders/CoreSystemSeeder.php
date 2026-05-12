<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Role;
use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CoreSystemSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::pluck('id', 'code');
        $roleAdmin = Role::where('name', 'admin')->first();
        $roleReceptionist = Role::where('name', 'receptionist')->first();
        $roleHousekeeping = Role::where('name', 'housekeeping')->first();
        $roleAccountant = Role::where('name', 'accountant')->first();

        /* 8 additional staff members across the 3 branches */
        $staffSeed = [
            ['STF-0002', 'Sok Pisey',     'female', 'Front Desk Manager', 600,  'BR-MAIN', $roleReceptionist],
            ['STF-0003', 'Chan Dara',     'male',   'Receptionist',       350,  'BR-MAIN', $roleReceptionist],
            ['STF-0004', 'Lim Sreyleak',  'female', 'Housekeeper',        280,  'BR-MAIN', $roleHousekeeping],
            ['STF-0005', 'Kong Vibol',    'male',   'Maintenance Tech',   420,  'BR-MAIN', null],
            ['STF-0006', 'Hor Sopheap',   'female', 'Accountant',         500,  'BR-MAIN', $roleAccountant],
            ['STF-0007', 'Nuon Channary', 'female', 'Branch Manager',     750,  'BR-BB',   $roleAdmin],
            ['STF-0008', 'Phon Sothea',   'male',   'Receptionist',       330,  'BR-BB',   $roleReceptionist],
            ['STF-0009', 'Touch Maly',    'female', 'Housekeeper',        270,  'BR-SR',   $roleHousekeeping],
            ['STF-0010', 'Ny Sokha',      'male',   'Receptionist',       340,  'BR-SR',   $roleReceptionist],
        ];

        foreach ($staffSeed as [$code, $name, $gender, $position, $salary, $branchCode, $role]) {
            $staff = Staff::firstOrCreate(
                ['staff_code' => $code],
                [
                    'branch_id'  => $branches[$branchCode] ?? null,
                    'full_name'  => $name,
                    'gender'     => $gender,
                    'phone'      => '0' . random_int(60000000, 99999999),
                    'email'      => strtolower(str_replace(' ', '.', $name)) . '@example.com',
                    'position'   => $position,
                    'salary'     => $salary,
                    'hire_date'  => now()->subMonths(random_int(1, 36))->toDateString(),
                    'status'     => 'active',
                ]
            );

            $username = strtolower(str_replace(' ', '', explode(' ', $name)[0])) . substr($code, -3);
            $user = User::firstOrCreate(
                ['email' => $staff->email],
                [
                    'branch_id' => $staff->branch_id,
                    'staff_id'  => $staff->id,
                    'name'      => $name,
                    'username'  => $username,
                    'phone'     => $staff->phone,
                    'password'  => Hash::make('password'),
                    'status'    => 'active',
                ]
            );

            if ($role) {
                $user->roles()->syncWithoutDetaching([$role->id]);
            }
        }

        /* 30 days of attendance per active staff (Mon-Sat, occasional late/leave) */
        $allStaff = Staff::where('status', 'active')->get();
        $period = CarbonPeriod::create(now()->subDays(30)->startOfDay(), now()->startOfDay());
        foreach ($allStaff as $staff) {
            foreach ($period as $day) {
                if ($day->isSunday()) {
                    continue;
                }
                $roll = random_int(1, 100);
                if ($roll <= 4) {
                    $status = 'leave';
                    $checkIn = null;
                    $checkOut = null;
                } elseif ($roll <= 8) {
                    $status = 'absent';
                    $checkIn = null;
                    $checkOut = null;
                } elseif ($roll <= 16) {
                    $status = 'late';
                    $checkIn = '08:' . str_pad((string) random_int(15, 55), 2, '0', STR_PAD_LEFT) . ':00';
                    $checkOut = '17:' . str_pad((string) random_int(0, 30), 2, '0', STR_PAD_LEFT) . ':00';
                } else {
                    $status = 'present';
                    $checkIn = '07:' . str_pad((string) random_int(45, 59), 2, '0', STR_PAD_LEFT) . ':00';
                    $checkOut = '17:' . str_pad((string) random_int(0, 30), 2, '0', STR_PAD_LEFT) . ':00';
                }

                $existingAttendance = StaffAttendance::query()
                    ->where('staff_id', $staff->id)
                    ->whereDate('attendance_date', $day->toDateString())
                    ->first();

                if ($existingAttendance) {
                    continue;
                }

                StaffAttendance::create([
                    'staff_id'       => $staff->id,
                    'attendance_date'=> $day->toDateString(),
                    'check_in_time'  => $checkIn,
                    'check_out_time' => $checkOut,
                    'status'         => $status,
                    'note'           => null,
                    'created_by'     => 1,
                ]);
            }
        }
    }
}
