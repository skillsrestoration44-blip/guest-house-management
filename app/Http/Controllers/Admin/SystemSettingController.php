<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\SystemSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SystemSettingController extends BaseCrudController
{
    protected string $modelClass = SystemSetting::class;
    protected string $route = 'admin.system_settings';
    protected string $viewPath = 'admin.system_settings';
    protected string $titleKey = 'system_settings';
    protected bool $hasBranchScope = false;
    protected array $eagerLoad = [];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'key' => 'required|string|max:150|unique:system_settings,key,' . ($model?->id ?? 'NULL'),
            'setting_group' => 'nullable|string|max:100',
            'type' => 'required|string|max:50',
            'value' => 'nullable|string',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'key'],
            ['data' => 'setting_group'],
            ['data' => 'type'],
            ['data' => 'action', 'orderable' => false, 'searchable' => false],
        ];
    }

    protected function formViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'key',
    'required' => true,
  ),
  1 => 
  array (
    'name' => 'setting_group',
  ),
  2 => 
  array (
    'name' => 'type',
    'default' => 'string',
  ),
  3 => 
  array (
    'name' => 'value',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'system_settings',
        ];
    }

    protected function showViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'key',
    'required' => true,
  ),
  1 => 
  array (
    'name' => 'setting_group',
  ),
  2 => 
  array (
    'name' => 'type',
    'default' => 'string',
  ),
  3 => 
  array (
    'name' => 'value',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'system_settings',
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