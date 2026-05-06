<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ServiceController extends BaseCrudController
{
    protected string $modelClass = Service::class;
    protected string $route = 'admin.services';
    protected string $viewPath = 'admin.services';
    protected string $titleKey = 'services';
    protected bool $hasBranchScope = false;
    protected array $eagerLoad = [];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'name' => 'required|string|max:150',
            'category' => 'nullable|string|max:100',
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'name'],
            ['data' => 'category'],
            ['data' => 'unit'],
            ['data' => 'price'],
            ['data' => 'status'],
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
    'name' => 'category',
  ),
  2 => 
  array (
    'name' => 'unit',
    'default' => 'unit',
  ),
  3 => 
  array (
    'name' => 'price',
    'type' => 'number',
    'step' => '0.01',
    'required' => true,
  ),
  4 => 
  array (
    'name' => 'description',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
  5 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'active' => 'Active',
      'inactive' => 'Inactive',
    ),
    'default' => 'active',
  ),
),
            'titleKey' => 'services',
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
    'name' => 'category',
  ),
  2 => 
  array (
    'name' => 'unit',
    'default' => 'unit',
  ),
  3 => 
  array (
    'name' => 'price',
    'type' => 'number',
    'step' => '0.01',
    'required' => true,
  ),
  4 => 
  array (
    'name' => 'description',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
  5 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'active' => 'Active',
      'inactive' => 'Inactive',
    ),
    'default' => 'active',
  ),
),
            'titleKey' => 'services',
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