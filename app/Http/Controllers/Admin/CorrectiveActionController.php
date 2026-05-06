<?php

namespace App\Http\Controllers\Admin;

use App\Models\CorrectiveAction;
use App\Models\User;
use App\Services\CodeGeneratorService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CorrectiveActionController extends BaseCrudController
{
    protected string $modelClass = CorrectiveAction::class;
    protected string $route = 'admin.corrective-actions';
    protected string $viewPath = 'admin.corrective_actions';
    protected string $titleKey = 'corrective_actions';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = ['owner'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'type' => 'required|in:corrective,preventive',
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'root_cause' => 'nullable|string',
            'action_taken' => 'nullable|string',
            'verification' => 'nullable|string',
            'target_date' => 'nullable|date',
            'completed_date' => 'nullable|date',
            'status' => 'required|in:open,in_progress,verifying,closed,cancelled',
            'owner_id' => 'nullable|exists:users,id',
            'source_type' => 'nullable|string|max:100',
            'source_id' => 'nullable|integer',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'capa_no'],
            ['data' => 'type'],
            ['data' => 'title'],
            ['data' => 'status'],
            ['data' => 'target_date'],
            ['data' => 'action', 'orderable' => false, 'searchable' => false],
        ];
    }

    protected function mutateData(Request $request, array $data, ?Model $model): array
    {
        if (!$model) {
            $data['capa_no'] = app(CodeGeneratorService::class)->next('capa');
        }
        return $data;
    }

    protected function formViewData(Model $model): array
    {
        return [
            'fields' => [
                ['name' => 'type', 'type' => 'select', 'required' => true,
                    'options' => ['corrective'=>'Corrective','preventive'=>'Preventive'],
                    'default' => 'corrective'],
                ['name' => 'title', 'required' => true],
                ['name' => 'description', 'type' => 'textarea', 'col' => 'col-12', 'required' => true],
                ['name' => 'root_cause', 'type' => 'textarea', 'col' => 'col-12'],
                ['name' => 'action_taken', 'type' => 'textarea', 'col' => 'col-12'],
                ['name' => 'verification', 'type' => 'textarea', 'col' => 'col-12'],
                ['name' => 'target_date', 'type' => 'date'],
                ['name' => 'completed_date', 'type' => 'date'],
                ['name' => 'status', 'type' => 'select', 'required' => true,
                    'options' => ['open'=>'Open','in_progress'=>'In progress','verifying'=>'Verifying','closed'=>'Closed','cancelled'=>'Cancelled'],
                    'default' => 'open'],
                ['name' => 'owner_id', 'i18n' => 'owner', 'type' => 'select',
                    'options' => User::pluck('name', 'id')->toArray()],
                ['name' => 'source_type', 'help' => 'Polymorphic type, e.g. App\\Models\\Complaint'],
                ['name' => 'source_id', 'type' => 'number'],
            ],
            'titleKey' => $this->titleKey,
        ];
    }

    protected function showViewData(Model $model): array
    {
        return $this->formViewData($model);
    }
}
