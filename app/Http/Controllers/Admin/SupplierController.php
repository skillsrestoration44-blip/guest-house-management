<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SupplierController extends BaseCrudController
{
    protected string $modelClass = Supplier::class;
    protected string $route = 'admin.suppliers';
    protected string $viewPath = 'admin.suppliers';
    protected string $titleKey = 'suppliers';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = [];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'contact_person' => 'nullable|string|max:150',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'name'],
            ['data' => 'phone'],
            ['data' => 'email'],
            ['data' => 'contact_person'],
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
    'name' => 'branch_id',
    'i18n' => 'branch',
    'type' => 'select',
    'options' => \App\Models\Branch::pluck('name', 'id')->toArray(),
  ),
  1 => 
  array (
    'name' => 'name',
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
    'name' => 'contact_person',
  ),
  5 => 
  array (
    'name' => 'address',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
  6 => 
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
            'titleKey' => 'suppliers',
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
    'name' => 'name',
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
    'name' => 'contact_person',
  ),
  5 => 
  array (
    'name' => 'address',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
  6 => 
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
            'titleKey' => 'suppliers',
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