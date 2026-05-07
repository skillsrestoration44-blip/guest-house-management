<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingGuest;
use App\Models\Guest;
use App\Models\Room;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        if (Booking::query()->exists()) {
            return;
        }

        /* Pick guests + rooms per branch and create realistic past/future bookings */
        $rooms = Room::with('roomType')->get()->groupBy('branch_id');
        $guests = Guest::where('is_blacklisted', false)->get()->groupBy('branch_id');

        $now = now()->startOfDay();
        $bookingScripts = [
            /* [days_before_today_for_check_in, nights, source, status] */
            [-21, 3, 'website',  'checked_out'],
            [-14, 2, 'walk_in',  'checked_out'],
            [-10, 4, 'phone',    'checked_out'],
            [-7,  2, 'agency',   'checked_out'],
            [-3,  3, 'website',  'checked_out'],
            [-1,  2, 'walk_in',  'checked_in'],
            [0,   1, 'phone',    'checked_in'],
            [2,   3, 'website',  'confirmed'],
            [5,   2, 'facebook', 'confirmed'],
            [9,   4, 'website',  'pending'],
            [-30, 2, 'walk_in',  'cancelled'],
            [-25, 1, 'phone',    'no_show'],
        ];

        foreach ($rooms as $branchId => $branchRooms) {
            $branchGuests = $guests->get($branchId, collect());
            if ($branchGuests->isEmpty() || $branchRooms->isEmpty()) {
                continue;
            }

            foreach ($bookingScripts as $i => [$offsetIn, $nights, $source, $status]) {
                $room = $branchRooms[$i % $branchRooms->count()];
                $guest = $branchGuests[$i % $branchGuests->count()];
                $checkIn = $now->copy()->addDays($offsetIn);
                $checkOut = $checkIn->copy()->addDays($nights);
                $price = (float) $room->price_per_night * $nights;

                $attrs = [
                    'branch_id'        => $branchId,
                    'guest_id'         => $guest->id,
                    'room_id'          => $room->id,
                    'booking_source'   => $source,
                    'check_in_date'    => $checkIn->toDateString(),
                    'check_out_date'   => $checkOut->toDateString(),
                    'check_in_time'    => '14:00:00',
                    'check_out_time'   => '12:00:00',
                    'adults'           => min(2, (int) $room->max_guests),
                    'children'         => 0,
                    'total_guests'     => min(2, (int) $room->max_guests),
                    'room_price'       => $price,
                    'deposit_amount'   => round($price * 0.3, 2),
                    'discount_amount'  => 0,
                    'status'           => $status,
                    'note'             => 'Sample seed booking',
                    'created_by'       => 1,
                ];
                if ($status === 'cancelled') {
                    $attrs['cancelled_by']  = 1;
                    $attrs['cancelled_at']  = $checkIn->copy()->subDay();
                    $attrs['cancel_reason'] = 'Guest changed plans';
                }
                $booking = Booking::create($attrs);

                BookingGuest::firstOrCreate(
                    ['booking_id' => $booking->id, 'guest_id' => $guest->id],
                    ['is_primary' => true]
                );
            }
        }
    }
}
