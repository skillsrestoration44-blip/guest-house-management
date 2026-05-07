<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCharge;
use App\Models\Stay;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['Breakfast Set',          'Food & Beverage', 'set',   5,    'Continental breakfast'],
            ['Dinner Set',             'Food & Beverage', 'set',   8,    'Three-course dinner'],
            ['Mineral Water 500ml',    'Mini Bar',        'bottle',1,    'Bottle of mineral water'],
            ['Beer (Local)',           'Mini Bar',        'can',   2,    'Local beer 330ml'],
            ['Laundry — Per Kg',       'Laundry',         'kg',    3,    'Wash, dry, fold'],
            ['Ironing — Per Item',     'Laundry',         'item',  1.5,  'Pressed and folded'],
            ['Airport Pickup',         'Transport',       'trip',  15,   'One-way airport transfer'],
            ['Tuk-tuk City Tour',      'Transport',       'trip',  10,   '2-hour city loop'],
            ['Bicycle Rental — Day',   'Transport',       'day',   3,    'Daily bike rental'],
            ['Spa Massage 60min',      'Wellness',        'session',20,  'Traditional Khmer massage'],
            ['Late Check-out',         'Other',           'unit',  10,   'Up to 4 extra hours'],
            ['Extra Bed',              'Room Add-on',     'night', 8,    'Additional bed'],
        ];

        $serviceModels = [];
        foreach ($services as [$name, $cat, $unit, $price, $desc]) {
            $serviceModels[$name] = Service::firstOrCreate(
                ['name' => $name],
                ['category' => $cat, 'unit' => $unit, 'price' => $price, 'description' => $desc, 'status' => 'active']
            );
        }

        /* Add a few service_charges to active stays */
        $stays = Stay::whereIn('status', ['checked_in', 'checked_out'])->get();
        foreach ($stays as $i => $stay) {
            $picks = match ($i % 4) {
                0 => ['Breakfast Set', 'Mineral Water 500ml'],
                1 => ['Laundry — Per Kg'],
                2 => ['Airport Pickup', 'Breakfast Set'],
                default => ['Spa Massage 60min'],
            };
            foreach ($picks as $svcName) {
                $svc = $serviceModels[$svcName];
                $qty = $svcName === 'Mineral Water 500ml' ? 4 : 1;
                $unitPrice = (float) $svc->price;
                ServiceCharge::firstOrCreate(
                    [
                        'stay_id'    => $stay->id,
                        'service_id' => $svc->id,
                    ],
                    [
                        'branch_id'   => $stay->branch_id,
                        'booking_id'  => $stay->booking_id,
                        'guest_id'    => $stay->guest_id,
                        'room_id'     => $stay->room_id,
                        'charge_date' => $stay->actual_check_in_at?->toDateString() ?? now()->toDateString(),
                        'quantity'    => $qty,
                        'unit_price'  => $unitPrice,
                        'total'       => $unitPrice * $qty,
                        'status'      => $stay->status === 'checked_out' ? 'billed' : 'pending',
                        'note'        => 'Sample charge',
                        'created_by'  => 1,
                    ]
                );
            }
        }
    }
}
