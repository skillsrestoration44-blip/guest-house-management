<?php

namespace Database\Seeders;

use App\Models\HousekeepingChecklistItem;
use App\Models\HousekeepingTask;
use App\Models\HousekeepingTaskCheck;
use App\Models\Room;
use App\Models\Staff;
use App\Services\CodeGeneratorService;
use Illuminate\Database\Seeder;

class HousekeepingSeeder extends Seeder
{
    public function run(): void
    {
        /* Standard daily-cleaning checklist */
        $items = [
            'Replace bed linen',
            'Replace towels',
            'Clean bathroom',
            'Restock toiletries',
            'Vacuum / mop floor',
            'Empty trash bins',
            'Wipe surfaces / mirror',
            'Check mini-bar',
            'Check air-conditioner & TV',
            'Final walk-through',
        ];
        $created = [];
        foreach ($items as $name) {
            $created[$name] = HousekeepingChecklistItem::firstOrCreate(
                ['name' => $name],
                ['description' => $name . ' (standard)', 'status' => 'active']
            );
        }

        $codeGen = app(CodeGeneratorService::class);
        $housekeepers = Staff::where('position', 'like', '%Housekeep%')->get();

        /* Pre-existing scheduled tasks (in addition to ones auto-created by StayObserver on check-out) */
        $rooms = Room::take(8)->get();
        foreach ($rooms as $i => $room) {
            $status = ['pending', 'cleaning', 'completed'][$i % 3];
            $scheduledAt = now()->startOfDay()->addDays($i - 4)->setTime(8, 0);

            $task = HousekeepingTask::query()
                ->where('room_id', $room->id)
                ->whereDate('scheduled_at', $scheduledAt->toDateString())
                ->first();

            if (!$task) {
                $task = HousekeepingTask::create([
                    'room_id'       => $room->id,
                    'scheduled_at'  => $scheduledAt,
                    'branch_id'     => $room->branch_id,
                    'task_no'       => $codeGen->next('housekeeping'),
                    'assigned_to'   => $housekeepers->isNotEmpty() ? $housekeepers->random()->id : null,
                    'started_at'    => $status !== 'pending' ? now()->subHours(1) : null,
                    'completed_at'  => $status === 'completed' ? now()->subMinutes(15) : null,
                    'status'        => $status,
                    'note'          => 'Daily cleaning seed',
                    'created_by'    => 1,
                ]);
            }

            /* Add 5 checklist items to each task */
            $picks = array_slice(array_keys($created), 0, 5);
            foreach ($picks as $name) {
                $isChecked = $task->status === 'completed';
                HousekeepingTaskCheck::firstOrCreate(
                    [
                        'housekeeping_task_id' => $task->id,
                        'checklist_item_id'    => $created[$name]->id,
                    ],
                    [
                        'is_checked'  => $isChecked,
                        'note'        => $isChecked ? 'OK' : null,
                        'photo_path'  => null,
                        'checked_by'  => $isChecked ? 1 : null,
                        'checked_at'  => $isChecked ? now()->subMinutes(20) : null,
                    ]
                );
            }
        }
    }
}
