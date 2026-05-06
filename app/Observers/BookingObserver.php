<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\BookingStatusHistory;
use App\Services\CodeGeneratorService;
use Illuminate\Support\Facades\Auth;

class BookingObserver
{
    public function creating(Booking $booking): void
    {
        if (empty($booking->booking_no)) {
            $booking->booking_no = app(CodeGeneratorService::class)->next('booking');
        }
        if (empty($booking->status)) {
            $booking->status = 'pending';
        }
    }

    public function created(Booking $booking): void
    {
        BookingStatusHistory::create([
            'booking_id' => $booking->id,
            'old_status' => null,
            'new_status' => $booking->status,
            'reason' => 'Booking created',
            'changed_by' => Auth::id(),
        ]);
    }

    public function updated(Booking $booking): void
    {
        if (!$booking->wasChanged('status')) {
            return;
        }
        BookingStatusHistory::create([
            'booking_id' => $booking->id,
            'old_status' => $booking->getOriginal('status'),
            'new_status' => $booking->status,
            'reason' => $booking->cancel_reason ?: 'Status changed',
            'changed_by' => Auth::id(),
        ]);
    }
}
