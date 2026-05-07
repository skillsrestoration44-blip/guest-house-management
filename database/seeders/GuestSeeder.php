<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Guest;
use App\Models\GuestDocument;
use App\Services\CodeGeneratorService;
use Illuminate\Database\Seeder;

class GuestSeeder extends Seeder
{
    public function run(): void
    {
        $codeGen = app(CodeGeneratorService::class);
        $branches = Branch::pluck('id', 'code');

        $guests = [
            ['Sou Sotheary',    'female', 'Cambodian', 'kh.s.sotheary@example.com',  '0712340001', '1992-03-15', 'BR-MAIN'],
            ['Heng Vanna',      'male',   'Cambodian', 'h.vanna@example.com',         '0712340002', '1985-07-22', 'BR-MAIN'],
            ['Mao Lyhuor',      'female', 'Cambodian', 'm.lyhuor@example.com',        '0712340003', '1996-11-04', 'BR-MAIN'],
            ['John Anderson',   'male',   'American',  'j.anderson@example.com',      '+15551230001', '1980-01-30', 'BR-MAIN'],
            ['Emma Williams',   'female', 'British',   'e.williams@example.com',      '+447700900001', '1990-05-18', 'BR-MAIN'],
            ['Yamada Hiroshi',  'male',   'Japanese',  'yamada@example.com',          '+819011112222', '1978-09-12', 'BR-BB'],
            ['Sofia Rossi',     'female', 'Italian',   's.rossi@example.com',         '+390123456789', '1994-12-01', 'BR-BB'],
            ['Pich Sokhom',     'male',   'Cambodian', 'p.sokhom@example.com',        '0712340007', '1989-02-25', 'BR-BB'],
            ['Kim Min-jun',     'male',   'Korean',    'k.minjun@example.com',        '+821012345678', '1983-04-09', 'BR-SR'],
            ['Nguyen Thi Hoa',  'female', 'Vietnamese','n.hoa@example.com',           '+84912345678', '1991-08-17', 'BR-SR'],
            ['Smith Michael',   'male',   'Australian','m.smith.au@example.com',      '+61400111222', '1976-06-06', 'BR-SR'],
            ['Chea Sreypov',    'female', 'Cambodian', 'c.sreypov@example.com',       '0712340011', '1998-10-29', 'BR-SR'],
        ];

        foreach ($guests as [$name, $gender, $nationality, $email, $phone, $dob, $branchCode]) {
            $guest = Guest::firstOrCreate(
                ['email' => $email],
                [
                    'branch_id'    => $branches[$branchCode] ?? null,
                    'guest_code'   => $codeGen->next('guest'),
                    'full_name'    => $name,
                    'gender'       => $gender,
                    'phone'        => $phone,
                    'nationality'  => $nationality,
                    'date_of_birth'=> $dob,
                    'address'      => $nationality === 'Cambodian' ? 'Phnom Penh, Cambodia' : 'International address',
                    'is_blacklisted' => false,
                    'note'         => null,
                ]
            );

            /* Attach an ID/passport doc */
            $type = $nationality === 'Cambodian' ? 'id_card' : 'passport';
            GuestDocument::firstOrCreate(
                ['guest_id' => $guest->id, 'document_type' => $type],
                [
                    'document_number' => $type === 'id_card' ? '0' . random_int(10000000, 99999999) : strtoupper(substr($name, 0, 1)) . random_int(1000000, 9999999),
                    'issue_date'      => now()->subYears(random_int(1, 5))->toDateString(),
                    'expiry_date'     => now()->addYears(random_int(2, 9))->toDateString(),
                    'file_path'       => "guests/{$guest->guest_code}-{$type}.jpg",
                    'created_by'      => 1,
                ]
            );
        }

        /* One blacklisted guest as a real-world example */
        $blacklist = Guest::firstOrCreate(
            ['email' => 'blacklisted@example.com'],
            [
                'branch_id'        => $branches['BR-MAIN'] ?? null,
                'guest_code'       => $codeGen->next('guest'),
                'full_name'        => 'Phantom Walker',
                'gender'           => 'male',
                'phone'            => '0712340099',
                'nationality'      => 'Unknown',
                'is_blacklisted'   => true,
                'blacklist_reason' => 'Damaged property and refused to pay (case #2025-014).',
                'note'             => 'Do not accept under any circumstances.',
            ]
        );
    }
}
