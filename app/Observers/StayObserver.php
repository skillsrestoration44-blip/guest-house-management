<?php

namespace App\Observers;

use App\Models\HousekeepingTask;
use App\Models\Room;
use App\Models\Stay;
use App\Services\CodeGeneratorService;
use Illuminate\Support\Facades\Auth;

class StayObserver
{
    public function creating(Stay $stay): void
    {
        if (empty($stay->stay_no)) {
            $stay->stay_no = app(CodeGeneratorService::class)->next('stay');
        }
        if (empty($stay->status)) {
            $stay->status = 'checked_in';
        }
        if (empty($stay->check_in_by)) {
            $stay->check_in_by = Auth::id();
        }
        if (empty($stay->actual_check_in_at)) {
            $stay->actual_check_in_at = now();
        }
    }

    public function created(Stay $stay): void
    {
        Room::whereKey($stay->room_id)->update(['status' => 'occupied']);
    }

    public function updated(Stay $stay): void
    {
        if ($stay->wasChanged('status')) {
            if ($stay->status === 'checked_out') {
                /* Free the room and queue a housekeeping task */
                Room::whereKey($stay->room_id)->update(['status' => 'cleaning']);
                if (!HousekeepingTask::where('room_id', $stay->room_id)
                    ->whereIn('status', ['pending', 'cleaning'])
                    ->exists()) {
                    HousekeepingTask::create([
                        'branch_id' => $stay->branch_id,
                        'task_no' => app(CodeGeneratorService::class)->next('housekeeping'),
                        'room_id' => $stay->room_id,
                        'assigned_to' => null,
                        'scheduled_at' => now(),
                        'status' => 'pending',
                        'note' => 'Auto-generated after check-out of stay #' . $stay->stay_no,
                    ]);
                }
            } elseif ($stay->status === 'transferred') {
                /* Room status managed by RoomTransfer */
            } elseif ($stay->status === 'cancelled') {
                Room::whereKey($stay->room_id)->update(['status' => 'available']);
            }
        }
    }
}
