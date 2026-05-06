<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\GuestFeedback;
use App\Models\Stay;
use App\Services\CodeGeneratorService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class GuestFeedbackController extends BaseCrudController
{
    protected string $modelClass = GuestFeedback::class;
    protected string $route = 'admin.guest-feedbacks';
    protected string $viewPath = 'admin.guest_feedbacks';
    protected string $titleKey = 'guest_feedbacks';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = ['guest', 'stay', 'booking'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'guest_id' => 'nullable|exists:guests,id',
            'stay_id' => 'nullable|exists:stays,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'cleanliness_score' => 'nullable|integer|min:1|max:5',
            'service_score' => 'nullable|integer|min:1|max:5',
            'value_score' => 'nullable|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'tags' => 'nullable|array',
            'status' => 'required|in:new,reviewed,addressed,closed',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'feedback_no'],
            ['data' => 'rating'],
            ['data' => 'status'],
            ['data' => 'created_at'],
            ['data' => 'action', 'orderable' => false, 'searchable' => false],
        ];
    }

    protected function mutateData(Request $request, array $data, ?Model $model): array
    {
        if (!$model) {
            $data['feedback_no'] = app(CodeGeneratorService::class)->next('feedback');
        }
        return $data;
    }

    protected function formViewData(Model $model): array
    {
        return [
            'fields' => [
                ['name' => 'guest_id', 'i18n' => 'guest', 'type' => 'select',
                    'options' => Guest::pluck('full_name', 'id')->toArray()],
                ['name' => 'stay_id', 'i18n' => 'stay', 'type' => 'select',
                    'options' => Stay::pluck('stay_no', 'id')->toArray()],
                ['name' => 'booking_id', 'i18n' => 'booking', 'type' => 'select',
                    'options' => Booking::pluck('booking_no', 'id')->toArray()],
                ['name' => 'rating', 'type' => 'select', 'required' => true,
                    'options' => [1=>'1 ★',2=>'2 ★★',3=>'3 ★★★',4=>'4 ★★★★',5=>'5 ★★★★★']],
                ['name' => 'cleanliness_score', 'type' => 'select',
                    'options' => [1=>1,2=>2,3=>3,4=>4,5=>5]],
                ['name' => 'service_score', 'type' => 'select',
                    'options' => [1=>1,2=>2,3=>3,4=>4,5=>5]],
                ['name' => 'value_score', 'type' => 'select',
                    'options' => [1=>1,2=>2,3=>3,4=>4,5=>5]],
                ['name' => 'comment', 'type' => 'textarea', 'col' => 'col-12'],
                ['name' => 'status', 'type' => 'select', 'required' => true,
                    'options' => ['new'=>'New','reviewed'=>'Reviewed','addressed'=>'Addressed','closed'=>'Closed'],
                    'default' => 'new'],
            ],
            'titleKey' => $this->titleKey,
        ];
    }

    protected function showViewData(Model $model): array
    {
        return $this->formViewData($model);
    }
}
