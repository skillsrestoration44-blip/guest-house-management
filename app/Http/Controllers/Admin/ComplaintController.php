<?php

namespace App\Http\Controllers\Admin;

use App\Models\Complaint;
use App\Models\Guest;
use App\Models\Stay;
use App\Models\User;
use App\Services\CodeGeneratorService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends BaseCrudController
{
    protected string $modelClass = Complaint::class;
    protected string $route = 'admin.complaints';
    protected string $viewPath = 'admin.complaints';
    protected string $titleKey = 'complaints';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = ['guest', 'stay', 'assignee'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'guest_id' => 'nullable|exists:guests,id',
            'stay_id' => 'nullable|exists:stays,id',
            'subject' => 'required|string|max:200',
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:open,investigating,resolved,rejected',
            'assigned_to' => 'nullable|exists:users,id',
            'resolution' => 'nullable|string',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'complaint_no'],
            ['data' => 'subject'],
            ['data' => 'severity'],
            ['data' => 'status'],
            ['data' => 'created_at'],
            ['data' => 'action', 'orderable' => false, 'searchable' => false],
        ];
    }

    protected function mutateData(Request $request, array $data, ?Model $model): array
    {
        if (!$model) {
            $data['complaint_no'] = app(CodeGeneratorService::class)->next('complaint');
            $data['reported_by'] = Auth::id();
        }
        if (($data['status'] ?? '') === 'resolved' && !($model?->resolved_at)) {
            $data['resolved_at'] = now();
            $data['resolved_by'] = Auth::id();
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
                ['name' => 'subject', 'required' => true],
                ['name' => 'description', 'type' => 'textarea', 'col' => 'col-12', 'required' => true],
                ['name' => 'severity', 'type' => 'select', 'required' => true,
                    'options' => ['low'=>'Low','medium'=>'Medium','high'=>'High','critical'=>'Critical'],
                    'default' => 'medium'],
                ['name' => 'status', 'type' => 'select', 'required' => true,
                    'options' => ['open'=>'Open','investigating'=>'Investigating','resolved'=>'Resolved','rejected'=>'Rejected'],
                    'default' => 'open'],
                ['name' => 'assigned_to', 'i18n' => 'assigned_to', 'type' => 'select',
                    'options' => User::pluck('name', 'id')->toArray()],
                ['name' => 'resolution', 'type' => 'textarea', 'col' => 'col-12'],
            ],
            'titleKey' => $this->titleKey,
        ];
    }

    protected function showViewData(Model $model): array
    {
        return $this->formViewData($model);
    }
}
