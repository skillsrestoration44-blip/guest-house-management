<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Complaint;
use App\Models\CorrectiveAction;
use App\Models\DocumentVersion;
use App\Models\GuestFeedback;
use App\Models\Risk;
use App\Models\Stay;
use App\Models\Supplier;
use App\Models\SupplierScorecard;
use App\Models\WebsitePage;
use App\Services\CodeGeneratorService;
use Illuminate\Database\Seeder;

class Iso9001Seeder extends Seeder
{
    public function run(): void
    {
        $codeGen = app(CodeGeneratorService::class);
        $branches = Branch::pluck('id', 'code');
        $closedStays = Stay::where('status', 'checked_out')->get();

        /* 1. Guest feedback (§5.2 / §8.2.1) — one feedback per closed stay */
        $samples = [
            [5, 5, 5, 5, 'Excellent service and very clean rooms!',  ['cleanliness','service'],     'reviewed'],
            [4, 5, 4, 4, 'Good experience, breakfast could improve.', ['breakfast'],                 'addressed'],
            [3, 3, 3, 4, 'Decent room. Wi-Fi was slow at night.',     ['wifi','noise'],              'new'],
            [5, 5, 5, 5, 'Friendly staff, would book again.',         ['service'],                   'closed'],
            [2, 3, 2, 2, 'Air-con noisy. Towels not replaced.',       ['ac','linen'],                'new'],
        ];
        foreach ($closedStays as $i => $stay) {
            [$rating, $clean, $service, $value, $comment, $tags, $status] = $samples[$i % count($samples)];
            GuestFeedback::firstOrCreate(
                ['stay_id' => $stay->id],
                [
                    'branch_id'         => $stay->branch_id,
                    'feedback_no'       => $codeGen->next('feedback'),
                    'guest_id'          => $stay->guest_id,
                    'booking_id'        => $stay->booking_id,
                    'rating'            => $rating,
                    'cleanliness_score' => $clean,
                    'service_score'     => $service,
                    'value_score'       => $value,
                    'comment'           => $comment,
                    'tags'              => $tags,
                    'status'            => $status,
                    'reviewed_by'       => $status !== 'new' ? 1 : null,
                    'reviewed_at'       => $status !== 'new' ? $stay->actual_check_out_at?->copy()->addHours(2) : null,
                ]
            );
        }

        /* 2. Complaints (§8.5.2) */
        $complaints = [
            ['Loud noise from corridor at night', 'Guest reported repeated loud noise from the corridor between 22:00–01:00.', 'high',     'investigating'],
            ['Hot water unavailable',             'Hot water was unavailable for ~3 hours in the morning.',                    'medium',   'resolved'],
            ['Bedsheets stained',                 'Sheets in room had a small stain on arrival.',                              'low',      'resolved'],
            ['Wi-Fi not working',                 'Guest could not connect to Wi-Fi for the entire afternoon.',                'medium',   'open'],
            ['Charged twice for breakfast',       'Guest claims they were billed twice for the breakfast set.',                'high',     'resolved'],
        ];
        $allClosedStays = $closedStays->values();
        foreach ($complaints as $i => [$subject, $desc, $severity, $status]) {
            $stay = $allClosedStays->get($i);
            if (!$stay) {
                continue;
            }
            Complaint::firstOrCreate(
                ['subject' => $subject, 'stay_id' => $stay->id],
                [
                    'branch_id'    => $stay->branch_id,
                    'complaint_no' => $codeGen->next('complaint'),
                    'guest_id'     => $stay->guest_id,
                    'description'  => $desc,
                    'severity'     => $severity,
                    'status'       => $status,
                    'assigned_to'  => 1,
                    'resolution'   => $status === 'resolved' ? 'Issue investigated and addressed; guest informed and compensated where applicable.' : null,
                    'resolved_at'  => $status === 'resolved' ? now()->subDays(2) : null,
                    'resolved_by'  => $status === 'resolved' ? 1 : null,
                    'reported_by'  => 1,
                ]
            );
        }

        /* 3. CAPA (§8.5.2 / §8.5.3) */
        $capas = [
            ['corrective', 'App\\Models\\Complaint', 'Add corridor noise signage and silent-hours policy', 'Recurring noise complaints in corridor zone.', 'in_progress'],
            ['corrective', 'App\\Models\\Complaint', 'Hot-water heater preventive maintenance schedule',   'Hot-water outage caused by aging heater unit.', 'closed'],
            ['preventive', null,                     'Quarterly Wi-Fi capacity review',                   'Detected slow Wi-Fi during peak hours.',        'open'],
            ['preventive', null,                     'Daily linen quality inspection',                    'Avoid stained-linen complaints (prevention).',   'verifying'],
            ['corrective', 'App\\Models\\Complaint', 'POS double-charge defensive checks',                'POS occasionally records duplicate breakfast charges.', 'in_progress'],
        ];
        foreach ($capas as $i => [$type, $sourceType, $title, $desc, $status]) {
            $sourceComplaint = Complaint::orderBy('id')->skip($i)->first();
            CorrectiveAction::firstOrCreate(
                ['title' => $title],
                [
                    'branch_id'    => $sourceComplaint?->branch_id ?? Branch::value('id'),
                    'capa_no'      => $codeGen->next('capa'),
                    'type'         => $type,
                    'source_type'  => $sourceType,
                    'source_id'    => $sourceType ? $sourceComplaint?->id : null,
                    'description'  => $desc,
                    'root_cause'   => $type === 'corrective' ? 'Root cause identified during investigation.' : null,
                    'action_taken' => $status === 'closed' ? 'Action implemented and verified by management.' : 'Action plan being executed.',
                    'verification' => $status === 'closed' ? 'Verified — no recurrence in the last 30 days.' : null,
                    'target_date'  => now()->addDays(($i + 1) * 7)->toDateString(),
                    'completed_date'=> $status === 'closed' ? now()->subDays(3)->toDateString() : null,
                    'status'       => $status,
                    'owner_id'     => 1,
                ]
            );
        }

        /* 4. Risks (§6.1) — risk_score is auto-computed by the Risk model */
        $risks = [
            ['Power outage during peak season',     'operational',  3, 4, 'Quarterly generator drill and fuel reserves',                                  'identified'],
            ['Currency fluctuation impact',         'financial',    4, 3, 'Hold reserves in USD; regularly review pricing.',                              'mitigating'],
            ['Fire safety non-compliance',          'safety',       2, 5, 'Monthly extinguisher checks; annual fire-marshal training.',                   'mitigating'],
            ['Data privacy breach',                 'compliance',   2, 4, 'Encryption at rest, RBAC, audit logs of every data access.',                   'mitigating'],
            ['Negative online reviews',             'reputational', 4, 3, 'Daily feedback review by branch manager; rapid response on review platforms.', 'identified'],
            ['Supply-chain disruption (linen)',     'operational',  3, 3, 'Two approved suppliers + 30-day buffer stock.',                                'accepted'],
        ];
        foreach ($risks as $i => [$title, $cat, $likelihood, $impact, $plan, $status]) {
            Risk::firstOrCreate(
                ['title' => $title],
                [
                    'branch_id'        => $branches['BR-MAIN'] ?? null,
                    'risk_no'          => $codeGen->next('risk'),
                    'category'         => $cat,
                    'description'      => $title . ' — registered as part of risk-based thinking review.',
                    'likelihood'       => $likelihood,
                    'impact'           => $impact,
                    'mitigation_plan'  => $plan,
                    'owner_id'         => 1,
                    'review_date'      => now()->addMonths(3)->toDateString(),
                    'status'           => $status,
                ]
            );
        }

        /* 5. Supplier Scorecards (§7.4) — one per supplier per period */
        $suppliers = Supplier::all();
        foreach ($suppliers as $i => $supplier) {
            $start = now()->subMonths(3)->startOfQuarter()->toDateString();
            $end   = now()->subMonths(3)->endOfQuarter()->toDateString();
            $q  = [4, 5, 4, 5][$i % 4];
            $d  = [4, 4, 5, 4][$i % 4];
            $p  = [3, 4, 4, 5][$i % 4];
            $c  = [4, 5, 3, 4][$i % 4];
            $overall = round(($q + $d + $p + $c) / 4, 2);
            SupplierScorecard::firstOrCreate(
                ['supplier_id' => $supplier->id, 'period_start' => $start],
                [
                    'period_end'           => $end,
                    'quality_score'        => $q,
                    'delivery_score'       => $d,
                    'price_score'          => $p,
                    'communication_score'  => $c,
                    'overall_score'        => $overall,
                    'comments'             => 'Quarterly evaluation — ' . $supplier->name,
                    'evaluated_by'         => 1,
                ]
            );
        }

        /* 6. Document Versions (§4.2.3) — track 2 revisions of the home page policy */
        $home = WebsitePage::where('slug', 'home')->first();
        if ($home) {
            DocumentVersion::firstOrCreate(
                ['versionable_type' => WebsitePage::class, 'versionable_id' => $home->id, 'version_number' => 1],
                [
                    'snapshot'    => $home->only(['slug', 'title', 'content', 'meta_title', 'meta_description', 'status']),
                    'change_note' => 'Initial revision',
                    'created_by'  => 1,
                ]
            );
            DocumentVersion::firstOrCreate(
                ['versionable_type' => WebsitePage::class, 'versionable_id' => $home->id, 'version_number' => 2],
                [
                    'snapshot'    => array_merge($home->only(['slug', 'title', 'content', 'meta_title', 'meta_description', 'status']), [
                        'content' => '<h2>Welcome (revised)</h2><p>Updated welcome copy with new branch info.</p>',
                    ]),
                    'change_note' => 'Updated welcome copy after rebrand',
                    'created_by'  => 1,
                ]
            );
        }
    }
}
