<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\OnlineBookingRequest;
use App\Models\PaymentMethod;
use App\Models\RoomType;
use App\Models\WebsitePage;
use Illuminate\Database\Seeder;

class WebsiteSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            ['home',         'Welcome to Sample Guest House',
                "<h2>Welcome</h2><p>A comfortable stay in the heart of Phnom Penh, Battambang and Siem Reap.</p>",
                'Sample Guest House — Cambodia',
                'Affordable comfortable rooms in Phnom Penh, Battambang and Siem Reap.', 'published'],
            ['rooms',        'Our Rooms',
                "<p>From budget dorms to VIP suites — find the perfect room for your stay.</p>",
                'Our Rooms — Sample Guest House',
                'Single, double, twin, family and suite rooms.', 'published'],
            ['about-us',     'About Us',
                "<p>We are a family-run guest house with three branches across Cambodia.</p>",
                'About — Sample Guest House',
                'Family-run guest house — three branches across Cambodia.', 'published'],
            ['contact',      'Contact Us',
                "<p>Email: info@example.com<br>Phone: 012 345 678</p>",
                'Contact — Sample Guest House',
                'Get in touch with the Sample Guest House team.', 'published'],
            ['privacy',      'Privacy Policy',
                '<p>We respect your privacy. Personal data is used only for booking and stay management.</p>',
                'Privacy Policy', 'Privacy policy of Sample Guest House.', 'published'],
            ['terms',        'Terms of Service',
                '<p>These are the terms of service for using the Sample Guest House website.</p>',
                'Terms of Service', 'Terms of service for the Sample Guest House website.', 'draft'],
        ];
        foreach ($pages as [$slug, $title, $content, $metaTitle, $metaDesc, $status]) {
            WebsitePage::firstOrCreate(
                ['slug' => $slug],
                [
                    'title'            => $title,
                    'content'          => $content,
                    'meta_title'       => $metaTitle,
                    'meta_description' => $metaDesc,
                    'status'           => $status,
                ]
            );
        }

        $branches = Branch::pluck('id', 'code');
        $double = RoomType::where('name', 'Double')->first();
        $family = RoomType::where('name', 'Family')->first();
        $vip    = RoomType::where('name', 'VIP Suite')->first();
        $aba    = PaymentMethod::where('code', 'ABA')->first();
        $bank   = PaymentMethod::where('code', 'BANK')->first();

        $reqs = [
            ['Tan Visal',         '0712340201', 't.visal@example.com',     $double, 2,  20, 'pending',   'BR-MAIN', $aba],
            ['Maria Garcia',      '+34611123456','m.garcia@example.com',   $family, 4,  60, 'approved',  'BR-MAIN', $bank],
            ['David Chen',        '+85296123456','d.chen@example.com',     $vip,    2,  80, 'pending',   'BR-MAIN', $aba],
            ['Hannah Lee',        '+821055556666','h.lee@example.com',     $double, 2,  30, 'rejected',  'BR-BB',   $aba],
            ['Pak Sokunthea',     '0712340205', 'p.sokunthea@example.com', $family, 5,  60, 'pending',   'BR-SR',   $aba],
        ];

        foreach ($reqs as $i => [$name, $phone, $email, $roomType, $guests, $deposit, $status, $branchCode, $method]) {
            $checkInDate = now()->addDays(7 + $i)->toDateString();
            $existingRequest = OnlineBookingRequest::query()
                ->where('guest_name', $name)
                ->whereDate('check_in_date', $checkInDate)
                ->first();

            if ($existingRequest) {
                continue;
            }

            OnlineBookingRequest::create([
                'guest_name'         => $name,
                'check_in_date'      => $checkInDate,
                'branch_id'          => $branches[$branchCode] ?? null,
                'request_no'         => 'OBR-' . str_pad((string) ($i + 1), 6, '0', STR_PAD_LEFT),
                'phone'              => $phone,
                'email'              => $email,
                'room_type_id'       => $roomType?->id,
                'check_out_date'     => now()->addDays(7 + $i + 2)->toDateString(),
                'total_guests'       => $guests,
                'deposit_amount'     => $deposit,
                'payment_method_id'  => $method?->id,
                'payment_reference'  => 'WEB-' . random_int(10000, 99999),
                'status'             => $status,
                'note'               => 'Online booking request',
            ]);
        }
    }
}
