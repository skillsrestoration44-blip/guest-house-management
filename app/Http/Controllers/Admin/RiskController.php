<?php

namespace App\Http\Controllers\Admin;

use App\Models\Risk;
use App\Models\User;
use App\Services\CodeGeneratorService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RiskController extends BaseCrudController
{
    protected string $modelClass = Risk::class;
    protected string $route = 'admin.risks';
    protected string $viewPath = 'admin.risks';
    protected string $titleKey = 'risks';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = ['owner'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'category' => 'required|in:operational,financial,safety,compliance,reputational,other',
            'likelihood' => 'required|integer|min:1|max:5',
            'impact' => 'required|integer|min:1|max:5',
            'mitigation_plan' => 'nullable|string',
            'owner_id' => 'nullable|exists:users,id',
            'review_date' => 'nullable|date',
            'status' => 'required|in:identified,mitigating,accepted,closed',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'risk_no'],
            ['data' => 'title'],
            ['data' => 'category'],
            ['data' => 'risk_score'],
            ['data' => 'status'],
            ['data' => 'action', 'orderable' => false, 'searchable' => false],
        ];
    }

    protected function mutateData(Request $request, array $data, ?Model $model): array
    {
        if (!$model) {
            $data['risk_no'] = app(CodeGeneratorService::class)->next('risk');
        }
        return $data;
    }

    protected function formViewData(Model $model): array
    {
        $cat = ['operational'=>'Operational','financial'=>'Financial','safety'=>'Safety',
            'compliance'=>'Compliance','reputational'=>'Reputational','other'=>'Other'];
        $score = [1=>1,2=>2,3=>3,4=>4,5=>5];
        return [
            'fields' => [
                ['name' => 'title', 'required' => true],
                ['name' => 'description', 'type' => 'textarea', 'col' => 'col-12'],
                ['name' => 'category', 'type' => 'select', 'required' => true, 'options' => $cat,
                    'default' => 'operational'],
                ['name' => 'likelihood', 'type' => 'select', 'required' => true, 'options' => $score],
                ['name' => 'impact', 'type' => 'select', 'required' => true, 'options' => $score],
                ['name' => 'mitigation_plan', 'type' => 'textarea', 'col' => 'col-12'],
                ['name' => 'owner_id', 'i18n' => 'owner', 'type' => 'select',
                    'options' => User::pluck('name', 'id')->toArray()],
                ['name' => 'review_date', 'type' => 'date'],
                ['name' => 'status', 'type' => 'select', 'required' => true,
                    'options' => ['identified'=>'Identified','mitigating'=>'Mitigating','accepted'=>'Accepted','closed'=>'Closed'],
                    'default' => 'identified'],
            ],
            'titleKey' => $this->titleKey,
        ];
    }

    protected function showViewData(Model $model): array
    {
        return $this->formViewData($model);
    }
}
