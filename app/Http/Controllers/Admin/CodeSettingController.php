<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\CodeSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CodeSettingController extends BaseCrudController
{
    protected string $modelClass = CodeSetting::class;
    protected string $route = 'admin.code_settings';
    protected string $viewPath = 'admin.code_settings';
    protected string $titleKey = 'code_settings';
    protected bool $hasBranchScope = false;
    protected array $eagerLoad = [];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'code_type' => 'required|string|max:100|unique:code_settings,code_type,' . ($model?->id ?? 'NULL'),
            'prefix' => 'required|string|max:20',
            'next_number' => 'required|integer|min:1',
            'digit_length' => 'required|integer|min:1',
            'example' => 'nullable|string|max:100',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'code_type'],
            ['data' => 'prefix'],
            ['data' => 'next_number'],
            ['data' => 'digit_length'],
            ['data' => 'example'],
            ['data' => 'action', 'orderable' => false, 'searchable' => false],
        ];
    }

    protected function formViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'code_type',
    'required' => true,
  ),
  1 => 
  array (
    'name' => 'prefix',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'next_number',
    'type' => 'number',
    'default' => 1,
  ),
  3 => 
  array (
    'name' => 'digit_length',
    'type' => 'number',
    'default' => 5,
  ),
  4 => 
  array (
    'name' => 'example',
  ),
),
            'titleKey' => 'code_settings',
        ];
    }

    protected function showViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'code_type',
    'required' => true,
  ),
  1 => 
  array (
    'name' => 'prefix',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'next_number',
    'type' => 'number',
    'default' => 1,
  ),
  3 => 
  array (
    'name' => 'digit_length',
    'type' => 'number',
    'default' => 5,
  ),
  4 => 
  array (
    'name' => 'example',
  ),
),
            'titleKey' => 'code_settings',
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