<?php

namespace App\Http\Controllers\Admin;

use App\Models\Supplier;
use App\Models\SupplierScorecard;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierScorecardController extends BaseCrudController
{
    protected string $modelClass = SupplierScorecard::class;
    protected string $route = 'admin.supplier-scorecards';
    protected string $viewPath = 'admin.supplier_scorecards';
    protected string $titleKey = 'supplier_scorecards';
    protected bool $hasBranchScope = false;
    protected array $eagerLoad = ['supplier', 'evaluator'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'supplier_id' => 'required|exists:suppliers,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'quality_score' => 'required|integer|min:1|max:5',
            'delivery_score' => 'required|integer|min:1|max:5',
            'price_score' => 'required|integer|min:1|max:5',
            'communication_score' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'supplier.name', 'name' => 'supplier.name', 'titleKey' => 'supplier'],
            ['data' => 'period_start'],
            ['data' => 'period_end'],
            ['data' => 'overall_score'],
            ['data' => 'action', 'orderable' => false, 'searchable' => false],
        ];
    }

    protected function mutateData(Request $request, array $data, ?Model $model): array
    {
        if (!$model) {
            $data['evaluated_by'] = Auth::id();
        }
        return $data;
    }

    protected function formViewData(Model $model): array
    {
        $score = [1=>1,2=>2,3=>3,4=>4,5=>5];
        return [
            'fields' => [
                ['name' => 'supplier_id', 'i18n' => 'supplier', 'type' => 'select', 'required' => true,
                    'options' => Supplier::pluck('name', 'id')->toArray()],
                ['name' => 'period_start', 'type' => 'date', 'required' => true],
                ['name' => 'period_end', 'type' => 'date', 'required' => true],
                ['name' => 'quality_score', 'type' => 'select', 'required' => true, 'options' => $score],
                ['name' => 'delivery_score', 'type' => 'select', 'required' => true, 'options' => $score],
                ['name' => 'price_score', 'type' => 'select', 'required' => true, 'options' => $score],
                ['name' => 'communication_score', 'type' => 'select', 'required' => true, 'options' => $score],
                ['name' => 'comments', 'type' => 'textarea', 'col' => 'col-12'],
            ],
            'titleKey' => $this->titleKey,
        ];
    }

    protected function showViewData(Model $model): array
    {
        return $this->formViewData($model);
    }
}
