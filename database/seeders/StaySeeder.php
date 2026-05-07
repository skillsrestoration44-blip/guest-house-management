<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomTransfer;
use App\Models\Stay;
use App\Models\StayGuest;
use Illuminate\Database\Seeder;

class StaySeeder extends Seeder
{
    public function run(): void
    {
        if (Stay::query()->exists()) {
            return;
        }

        /* For every booking that has reached check-in, create a stay record. */
        $bookings = Booking::whereIn('status', ['checked_in', 'checked_out'])->get();
        foreach ($bookings as $booking) {
            $checkInAt = $booking->check_in_date->copy()->setTime(14, 0);
            $expectedOut = $booking->check_out_date->copy()->setTime(12, 0);
            $isCheckedOut = $booking->status === 'checked_out';

            $stay = Stay::create([
                'branch_id'             => $booking->branch_id,
                'booking_id'            => $booking->id,
                'guest_id'              => $booking->guest_id,
                'room_id'               => $booking->room_id,
                'actual_check_in_at'    => $checkInAt,
                'expected_check_out_at' => $expectedOut,
                'actual_check_out_at'   => $isCheckedOut ? $expectedOut->copy()->subMinutes(30) : null,
                'check_in_by'           => 1,
                'check_out_by'          => $isCheckedOut ? 1 : null,
                'room_price'            => $booking->room_price,
                'deposit_amount'        => $booking->deposit_amount,
                'damage_fee'            => 0,
                'late_checkout_fee'     => 0,
                'status'                => $isCheckedOut ? 'checked_out' : 'checked_in',
                'note'                  => null,
            ]);

            StayGuest::firstOrCreate(
                ['stay_id' => $stay->id, 'guest_id' => $booking->guest_id],
                ['is_primary' => true]
            );
        }

        /* Demonstrate one room transfer on a single past stay (older one) */
        $sample = Stay::where('status', 'checked_out')->orderBy('id')->first();
        if ($sample) {
            $alternate = Room::where('branch_id', $sample->branch_id)
                ->where('id', '!=', $sample->room_id)
                ->first();
            if ($alternate) {
                RoomTransfer::firstOrCreate(
                    [
                        'stay_id'      => $sample->id,
                        'from_room_id' => $sample->room_id,
                        'to_room_id'   => $alternate->id,
                    ],
                    [
                        'transfer_at'      => $sample->actual_check_in_at->copy()->addHours(20),
                        'price_difference' => round(((float) $alternate->price_per_night - (float) $sample->room_price), 2),
                        'reason'           => 'Guest requested quieter room',
                        'transferred_by'   => 1,
                    ]
                );
            }
        }
    }
}
