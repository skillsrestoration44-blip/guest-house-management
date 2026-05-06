<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class StaffController extends BaseCrudController
{
    protected string $modelClass = Staff::class;
    protected string $route = 'admin.staff';
    protected string $viewPath = 'admin.staff';
    protected string $titleKey = 'staff';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = [];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'staff_code' => 'required|string|max:50|unique:staff,staff_code,' . ($model?->id ?? 'NULL'),
            'full_name' => 'required|string|max:150',
            'gender' => 'nullable|in:male,female,other',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'address' => 'nullable|string',
            'position' => 'nullable|string|max:100',
            'salary' => 'nullable|numeric|min:0',
            'hire_date' => 'nullable|date',
            'photo' => 'nullable|file|image|max:4096',
            'status' => 'required|in:active,resigned,suspended',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id', 'titleKey' => 'staff_code'],
            ['data' => 'staff_code', 'titleKey' => 'staff_code'],
            ['data' => 'full_name', 'titleKey' => 'full_name'],
            ['data' => 'phone', 'titleKey' => 'phone'],
            ['data' => 'position', 'titleKey' => 'position'],
            ['data' => 'salary', 'titleKey' => 'salary'],
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
    'name' => 'branch_id',
    'i18n' => 'branch',
    'type' => 'select',
    'options' => \App\Models\Branch::pluck('name', 'id')->toArray(),
  ),
  1 => 
  array (
    'name' => 'staff_code',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'full_name',
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'gender',
    'type' => 'select',
    'options' => 
    array (
      'male' => 'Male',
      'female' => 'Female',
      'other' => 'Other',
    ),
  ),
  4 => 
  array (
    'name' => 'phone',
  ),
  5 => 
  array (
    'name' => 'email',
    'type' => 'email',
  ),
  6 => 
  array (
    'name' => 'position',
  ),
  7 => 
  array (
    'name' => 'salary',
    'type' => 'number',
    'step' => '0.01',
  ),
  8 => 
  array (
    'name' => 'hire_date',
    'type' => 'date',
  ),
  9 => 
  array (
    'name' => 'address',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
  10 => 
  array (
    'name' => 'photo',
    'type' => 'file',
  ),
  11 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'active' => 'Active',
      'resigned' => 'Resigned',
      'suspended' => 'Suspended',
    ),
    'default' => 'active',
  ),
),
            'titleKey' => 'staff',
        ];
    }

    protected function showViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'branch_id',
    'i18n' => 'branch',
    'type' => 'select',
    'options' => \App\Models\Branch::pluck('name', 'id')->toArray(),
  ),
  1 => 
  array (
    'name' => 'staff_code',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'full_name',
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'gender',
    'type' => 'select',
    'options' => 
    array (
      'male' => 'Male',
      'female' => 'Female',
      'other' => 'Other',
    ),
  ),
  4 => 
  array (
    'name' => 'phone',
  ),
  5 => 
  array (
    'name' => 'email',
    'type' => 'email',
  ),
  6 => 
  array (
    'name' => 'position',
  ),
  7 => 
  array (
    'name' => 'salary',
    'type' => 'number',
    'step' => '0.01',
  ),
  8 => 
  array (
    'name' => 'hire_date',
    'type' => 'date',
  ),
  9 => 
  array (
    'name' => 'address',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
  10 => 
  array (
    'name' => 'photo',
    'type' => 'file',
  ),
  11 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'active' => 'Active',
      'resigned' => 'Resigned',
      'suspended' => 'Suspended',
    ),
    'default' => 'active',
  ),
),
            'titleKey' => 'staff',
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