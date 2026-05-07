<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Facility;
use App\Models\FacilityRoom;
use App\Models\Room;
use App\Models\RoomImage;
use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomManagementSeeder extends Seeder
{
    public function run(): void
    {
        $defaultBranchId = Branch::where('is_default', true)->value('id');

        /* 6 room types — global (branch_id null) so any branch can use them */
        $types = [
            ['Single',          'One single bed for solo travellers',     22,  3,  1, 1],
            ['Double',          'Standard double room',                   30,  4,  2, 1],
            ['Twin',            'Two single beds',                        32,  4,  2, 2],
            ['Family',          'Spacious room with extra bed',           45,  6,  4, 2],
            ['VIP Suite',       'Suite with living area and balcony',     80, 10,  3, 1],
            ['Dormitory',       'Shared 6-bed budget dorm',               12,  2,  6, 6],
        ];
        $createdTypes = [];
        foreach ($types as [$name, $desc, $night, $hour, $max, $beds]) {
            $createdTypes[$name] = RoomType::firstOrCreate(
                ['name' => $name],
                [
                    'branch_id'                => $defaultBranchId,
                    'description'              => $desc,
                    'default_price_per_night'  => $night,
                    'default_price_per_hour'   => $hour,
                    'max_guests'               => $max,
                    'bed_count'                => $beds,
                    'status'                   => 'active',
                ]
            );
        }

        /* 10 facilities */
        $facilities = ['Wi-Fi', 'Air Conditioning', 'TV', 'Hot Water', 'Mini Bar', 'Safe Box', 'Hair Dryer', 'Balcony', 'City View', 'Kettle'];
        $createdFacilities = [];
        foreach ($facilities as $f) {
            $createdFacilities[$f] = Facility::firstOrCreate(
                ['name' => $f],
                ['branch_id' => $defaultBranchId, 'description' => "Room facility: {$f}", 'status' => 'active']
            );
        }

        /* Rooms across all 3 branches */
        $branches = Branch::all();
        $roomNumber = 100;
        foreach ($branches as $branch) {
            foreach ($createdTypes as $typeName => $type) {
                $count = match ($typeName) {
                    'Single' => 3,
                    'Double' => 4,
                    'Twin' => 2,
                    'Family' => 2,
                    'VIP Suite' => 1,
                    'Dormitory' => 1,
                    default => 1,
                };
                for ($i = 0; $i < $count; $i++) {
                    $roomNumber++;
                    $room = Room::firstOrCreate(
                        ['room_number' => (string) $roomNumber],
                        [
                            'branch_id'        => $branch->id,
                            'room_type_id'     => $type->id,
                            'floor'            => (string) (intdiv($roomNumber, 100)),
                            'bed_count'        => $type->bed_count,
                            'max_guests'       => $type->max_guests,
                            'price_per_night'  => $type->default_price_per_night,
                            'price_per_hour'   => $type->default_price_per_hour,
                            'status'           => 'available',
                            'description'      => "{$typeName} room #{$roomNumber}",
                        ]
                    );

                    /* Attach 4-6 facilities per room */
                    $facilityNames = match ($typeName) {
                        'VIP Suite' => ['Wi-Fi', 'Air Conditioning', 'TV', 'Hot Water', 'Mini Bar', 'Safe Box', 'Balcony', 'City View'],
                        'Family'    => ['Wi-Fi', 'Air Conditioning', 'TV', 'Hot Water', 'Hair Dryer', 'Kettle'],
                        'Dormitory' => ['Wi-Fi', 'Air Conditioning'],
                        default     => ['Wi-Fi', 'Air Conditioning', 'TV', 'Hot Water'],
                    };
                    foreach ($facilityNames as $fn) {
                        FacilityRoom::firstOrCreate(
                            ['room_id' => $room->id, 'facility_id' => $createdFacilities[$fn]->id],
                            ['quantity' => 1, 'item_condition' => 'good']
                        );
                    }

                    /* 1 placeholder room image per room */
                    RoomImage::firstOrCreate(
                        ['room_id' => $room->id, 'image_path' => "rooms/sample-{$roomNumber}.jpg"],
                        ['is_primary' => true]
                    );
                }
            }
        }
    }
}
