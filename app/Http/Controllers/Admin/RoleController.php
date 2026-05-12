<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RoleController extends BaseCrudController
{
    protected string $modelClass = Role::class;
    protected string $route = 'admin.roles';
    protected string $viewPath = 'admin.roles';
    protected string $titleKey = 'roles';
    protected bool $hasBranchScope = false;
    protected array $eagerLoad = [];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'name' => 'required|string|max:100|unique:roles,name,' . ($model?->id ?? 'NULL'),
            'display_name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        'permissions' => 'nullable|array',
        'permissions.*' => 'integer|exists:permissions,id',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id', 'titleKey' => 'name'],
            ['data' => 'name'],
            ['data' => 'display_name', 'titleKey' => 'name'],
            ['data' => 'status'],
            ['data' => 'action', 'orderable' => false, 'searchable' => false],
        ];
    }

    protected function formViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        $model->loadMissing('permissions');

        return [
            'titleKey' => 'roles',
            'permissionGroups' => $this->permissionGroups(),
            'selectedPermissionIds' => $model->permissions->pluck('id')->all(),
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
    'name' => 'display_name',
    'required' => true,
  ),
  2 =>
  array (
    'name' => 'description',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
  3 =>
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
            'titleKey' => 'roles',
        ];
    }

    protected function mutateData(Request $request, array $data, ?Model $model): array
    {
        return $data;
    }

    protected function afterStore(Request $request, Model $model): void
    {
      $model->permissions()->sync($request->input('permissions', []));
    }

    protected function afterUpdate(Request $request, Model $model): void
    {
      $model->permissions()->sync($request->input('permissions', []));
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

    private function permissionGroups(): array
    {
      return Permission::query()
        ->orderBy('module')
        ->orderBy('name')
        ->get()
        ->groupBy('module')
        ->map(function ($permissions, $module) {
          $translatedLabel = __('messages.' . $module);
          $label = $translatedLabel !== 'messages.' . $module
            ? $translatedLabel
            : str($module)->replace('_', ' ')->title()->toString();

          return [
            'label' => $label,
            'permissions' => $permissions->map(function (Permission $permission) {
              $rawLabel = $permission->display_name
                ?: str($permission->name)->after('.')->replace('_', ' ')->title()->toString();

              return [
                'id' => $permission->id,
                'label' => $rawLabel,
                'hint' => $permission->name,
              ];
            })->values()->all(),
          ];
        })
        ->all();
    }
}
