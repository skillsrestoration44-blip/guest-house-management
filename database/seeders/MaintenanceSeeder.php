<?php

namespace Database\Seeders;

use App\Models\MaintenanceCost;
use App\Models\MaintenancePhoto;
use App\Models\MaintenanceRequest;
use App\Models\Room;
use App\Models\Staff;
use App\Services\CodeGeneratorService;
use Illuminate\Database\Seeder;

class MaintenanceSeeder extends Seeder
{
    public function run(): void
    {
        if (MaintenanceRequest::query()->exists()) {
            return;
        }

        $codeGen = app(CodeGeneratorService::class);
        $rooms = Room::take(6)->get();
        $tech = Staff::where('position', 'like', '%Maintenance%')->first();

        $scripts = [
            ['Air-Con',  'Air-conditioner not cooling, suspected gas leak.',         'high',   'pending'],
            ['Plumbing', 'Bathroom shower drain clogged.',                           'medium', 'in_progress'],
            ['Electric', 'TV remote sensor not responding.',                         'low',    'completed'],
            ['Furniture','Bedside lamp wobbly, screw loose.',                        'low',    'completed'],
            ['Plumbing', 'Toilet flush handle broken.',                              'medium', 'completed'],
            ['Air-Con',  'Strange noise from air-conditioner unit during night.',    'urgent', 'waiting_material'],
        ];

        foreach ($rooms as $i => $room) {
            [$type, $desc, $priority, $status] = $scripts[$i % count($scripts)];

            $req = MaintenanceRequest::create([
                'branch_id'   => $room->branch_id,
                'request_no'  => $codeGen->next('request'),
                'room_id'     => $room->id,
                'reported_by' => 1,
                'assigned_to' => $tech?->id,
                'issue_type'  => $type,
                'description' => $desc,
                'priority'    => $priority,
                'status'      => $status,
                'reported_at' => now()->subDays($i + 1),
                'started_at'  => $status !== 'pending' ? now()->subDays($i)->addHours(2) : null,
                'completed_at'=> $status === 'completed' ? now()->subDays(max(0, $i - 1)) : null,
                'note'        => null,
            ]);

            MaintenancePhoto::create([
                'maintenance_request_id' => $req->id,
                'photo_path'             => "maintenance/{$req->request_no}-before.jpg",
                'type'                   => 'before',
            ]);
            if ($status === 'completed') {
                MaintenancePhoto::create([
                    'maintenance_request_id' => $req->id,
                    'photo_path'             => "maintenance/{$req->request_no}-after.jpg",
                    'type'                   => 'after',
                ]);
            }

            /* Cost lines */
            MaintenanceCost::create([
                'maintenance_request_id' => $req->id,
                'cost_type'              => 'material',
                'description'            => $type . ' parts',
                'amount'                 => round(random_int(500, 4500) / 100, 2),
                'created_by'             => 1,
            ]);
            MaintenanceCost::create([
                'maintenance_request_id' => $req->id,
                'cost_type'              => 'labor',
                'description'            => 'On-site labour',
                'amount'                 => round(random_int(800, 2500) / 100, 2),
                'created_by'             => 1,
            ]);
        }
    }
}
