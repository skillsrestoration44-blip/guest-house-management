<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\Backup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BackupController extends BaseCrudController
{
    protected string $modelClass = Backup::class;
    protected string $route = 'admin.backups';
    protected string $viewPath = 'admin.backups';
    protected string $titleKey = 'backups';
    protected bool $hasBranchScope = false;
    protected array $eagerLoad = [];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'file_name' => 'required|string|max:255',
            'file_path' => 'required|string|max:255',
            'file_size' => 'nullable|integer|min:0',
            'backup_type' => 'required|in:manual,auto',
            'status' => 'required|in:success,failed',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'file_name'],
            ['data' => 'file_size'],
            ['data' => 'backup_type'],
            ['data' => 'status'],
            ['data' => 'created_at'],
            ['data' => 'action', 'orderable' => false, 'searchable' => false],
        ];
    }

    protected function formViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'file_name',
    'required' => true,
  ),
  1 => 
  array (
    'name' => 'file_path',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'file_size',
    'type' => 'number',
  ),
  3 => 
  array (
    'name' => 'backup_type',
    'type' => 'select',
    'options' => 
    array (
      'manual' => 'Manual',
      'auto' => 'Auto',
    ),
    'default' => 'manual',
  ),
  4 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'success' => 'Success',
      'failed' => 'Failed',
    ),
    'default' => 'success',
  ),
),
            'titleKey' => 'backups',
        ];
    }

    protected function showViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'file_name',
    'required' => true,
  ),
  1 => 
  array (
    'name' => 'file_path',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'file_size',
    'type' => 'number',
  ),
  3 => 
  array (
    'name' => 'backup_type',
    'type' => 'select',
    'options' => 
    array (
      'manual' => 'Manual',
      'auto' => 'Auto',
    ),
    'default' => 'manual',
  ),
  4 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'success' => 'Success',
      'failed' => 'Failed',
    ),
    'default' => 'success',
  ),
),
            'titleKey' => 'backups',
        ];
    }

    protected function mutateData(Request $request, array $data, ?Model $model): array
    {
        return $data;
    }

    protected function afterStore(\Illuminate\Http\Request $request, \Illuminate\Database\Eloquent\Model $model): void
    {
        if (in_array('created_by', $model->getFillable(), true) || \Illuminate\Support\Facades\Schema::hasColumn($model->getTable(), 'created_by')) {
            $model->forceFill(['created_by' => auth()->id()])->save();
        }
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