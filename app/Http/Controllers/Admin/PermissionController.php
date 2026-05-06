<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PermissionController extends BaseCrudController
{
    protected string $modelClass = Permission::class;
    protected string $route = 'admin.permissions';
    protected string $viewPath = 'admin.permissions';
    protected string $titleKey = 'permissions';
    protected bool $hasBranchScope = false;
    protected array $eagerLoad = [];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'module' => 'required|string|max:100',
            'name' => 'required|string|max:150|unique:permissions,name,' . ($model?->id ?? 'NULL'),
            'display_name' => 'required|string|max:150',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id', 'titleKey' => 'name'],
            ['data' => 'module'],
            ['data' => 'name'],
            ['data' => 'display_name'],
            ['data' => 'action', 'orderable' => false, 'searchable' => false],
        ];
    }

    protected function formViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'module',
    'required' => true,
  ),
  1 => 
  array (
    'name' => 'name',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'display_name',
    'required' => true,
  ),
),
            'titleKey' => 'permissions',
        ];
    }

    protected function showViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'module',
    'required' => true,
  ),
  1 => 
  array (
    'name' => 'name',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'display_name',
    'required' => true,
  ),
),
            'titleKey' => 'permissions',
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