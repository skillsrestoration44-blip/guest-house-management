<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BranchController extends BaseCrudController
{
    protected string $modelClass = Branch::class;
    protected string $route = 'admin.branches';
    protected string $viewPath = 'admin.branches';
    protected string $titleKey = 'branches';
    protected bool $hasBranchScope = false;
    protected array $eagerLoad = [];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'code' => 'required|string|max:50|unique:branches,code,' . ($model?->id ?? 'NULL'),
            'name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'manager_name' => 'nullable|string|max:150',
            'address' => 'nullable|string',
            'is_default' => 'nullable|boolean',
            'status' => 'required|in:active,inactive',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id', 'name' => 'id', 'titleKey' => 'code'],
            ['data' => 'code', 'titleKey' => 'branch_code'],
            ['data' => 'name', 'titleKey' => 'branch_name'],
            ['data' => 'phone', 'titleKey' => 'phone'],
            ['data' => 'manager_name', 'titleKey' => 'manager_name'],
            ['data' => 'status', 'titleKey' => 'status'],
            ['data' => 'action', 'titleKey' => 'action', 'orderable' => false, 'searchable' => false],
        ];
    }

    protected function formViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'code',
    'i18n' => 'branch_code',
    'required' => true,
  ),
  1 => 
  array (
    'name' => 'name',
    'i18n' => 'branch_name',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'phone',
  ),
  3 => 
  array (
    'name' => 'email',
    'type' => 'email',
  ),
  4 => 
  array (
    'name' => 'manager_name',
  ),
  5 => 
  array (
    'name' => 'address',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
  6 => 
  array (
    'name' => 'is_default',
    'type' => 'checkbox',
  ),
  7 => 
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
            'titleKey' => 'branches',
        ];
    }

    protected function showViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'code',
    'i18n' => 'branch_code',
    'required' => true,
  ),
  1 => 
  array (
    'name' => 'name',
    'i18n' => 'branch_name',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'phone',
  ),
  3 => 
  array (
    'name' => 'email',
    'type' => 'email',
  ),
  4 => 
  array (
    'name' => 'manager_name',
  ),
  5 => 
  array (
    'name' => 'address',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
  6 => 
  array (
    'name' => 'is_default',
    'type' => 'checkbox',
  ),
  7 => 
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
            'titleKey' => 'branches',
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