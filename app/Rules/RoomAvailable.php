<?php

namespace App\Rules;

use App\Models\Booking;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Reject overlapping bookings on the same room.
 *
 * Usage:
 *   'check_in_date'  => ['required', 'date'],
 *   'check_out_date' => ['required', 'date', 'after_or_equal:check_in_date',
 *                        new RoomAvailable($request->input('room_id'),
 *                                          $request->input('check_in_date'),
 *                                          $request->input('check_out_date'),
 *                                          $bookingId /* nullable for create *\/)],
 */
class RoomAvailable implements ValidationRule
{
    public function __construct(
        protected ?int $roomId,
        protected ?string $checkInDate,
        protected ?string $checkOutDate,
        protected ?int $excludeBookingId = null,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->roomId || !$this->checkInDate || !$this->checkOutDate) {
            return;
        }
        $conflict = Booking::query()
            ->where('room_id', $this->roomId)
            ->whereNotIn('status', ['cancelled', 'no_show', 'checked_out'])
            ->when($this->excludeBookingId, fn ($q) => $q->where('id', '!=', $this->excludeBookingId))
            ->where(function ($q) {
                $q->where(function ($qq) {
                    $qq->where('check_in_date', '<', $this->checkOutDate)
                        ->where('check_out_date', '>', $this->checkInDate);
                });
            })
            ->exists();
        if ($conflict) {
            $fail(__('messages.room_already_booked'));
        }
    }
}
