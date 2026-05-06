<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\StockCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class StockCategoryController extends BaseCrudController
{
    protected string $modelClass = StockCategory::class;
    protected string $route = 'admin.stock_categories';
    protected string $viewPath = 'admin.stock_categories';
    protected string $titleKey = 'stock_categories';
    protected bool $hasBranchScope = false;
    protected array $eagerLoad = [];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'name'],
            ['data' => 'description'],
            ['data' => 'action', 'orderable' => false, 'searchable' => false],
        ];
    }

    protected function formViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'name',
    'required' => true,
  ),
  1 => 
  array (
    'name' => 'description',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'stock_categories',
        ];
    }

    protected function showViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'name',
    'required' => true,
  ),
  1 => 
  array (
    'name' => 'description',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'stock_categories',
        ];
    }

    protected function mutateData(Request $request, array $data, ?Model $model): array
    {
        return $data;
    }

    protected function indexViewData(\Illuminate\Http\Request $request): array
    {
        return [
            'route' => $this->route,
            'columns' => $this->tableColumns(),
            'titleKey' => $this->titleKey,
            'readOnly' => false,
        ];
    }
}