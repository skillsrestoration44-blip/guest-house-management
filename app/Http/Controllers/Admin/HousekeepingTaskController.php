<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\HousekeepingTask;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class HousekeepingTaskController extends BaseCrudController
{
    protected string $modelClass = HousekeepingTask::class;
    protected string $route = 'admin.housekeeping_tasks';
    protected string $viewPath = 'admin.housekeeping_tasks';
    protected string $titleKey = 'housekeeping_tasks';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = ['room', 'assignee'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'task_no' => 'required|string|max:50|unique:housekeeping_tasks,task_no,' . ($model?->id ?? 'NULL'),
            'room_id' => 'required|exists:rooms,id',
            'assigned_to' => 'nullable|exists:staff,id',
            'scheduled_at' => 'required|date',
            'started_at' => 'nullable|date',
            'completed_at' => 'nullable|date',
            'status' => 'required|in:pending,cleaning,completed,cancelled',
            'note' => 'nullable|string',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'task_no'],
            ['data' => 'room.room_number', 'titleKey' => 'room'],
            ['data' => 'assignee.full_name', 'titleKey' => 'staff'],
            ['data' => 'scheduled_at'],
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
    'name' => 'task_no',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'room_id',
    'i18n' => 'room',
    'type' => 'select',
    'options' => \App\Models\Room::pluck('room_number', 'id')->toArray(),
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'assigned_to',
    'i18n' => 'staff',
    'type' => 'select',
    'options' => \App\Models\Staff::pluck('full_name', 'id')->toArray(),
  ),
  4 => 
  array (
    'name' => 'scheduled_at',
    'type' => 'datetime',
    'required' => true,
  ),
  5 => 
  array (
    'name' => 'started_at',
    'type' => 'datetime',
  ),
  6 => 
  array (
    'name' => 'completed_at',
    'type' => 'datetime',
  ),
  7 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'pending' => 'Pending',
      'cleaning' => 'Cleaning',
      'completed' => 'Completed',
      'cancelled' => 'Cancelled',
    ),
    'default' => 'pending',
  ),
  8 => 
  array (
    'name' => 'note',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'housekeeping_tasks',
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
    'name' => 'task_no',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'room_id',
    'i18n' => 'room',
    'type' => 'select',
    'options' => \App\Models\Room::pluck('room_number', 'id')->toArray(),
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'assigned_to',
    'i18n' => 'staff',
    'type' => 'select',
    'options' => \App\Models\Staff::pluck('full_name', 'id')->toArray(),
  ),
  4 => 
  array (
    'name' => 'scheduled_at',
    'type' => 'datetime',
    'required' => true,
  ),
  5 => 
  array (
    'name' => 'started_at',
    'type' => 'datetime',
  ),
  6 => 
  array (
    'name' => 'completed_at',
    'type' => 'datetime',
  ),
  7 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'pending' => 'Pending',
      'cleaning' => 'Cleaning',
      'completed' => 'Completed',
      'cancelled' => 'Cancelled',
    ),
    'default' => 'pending',
  ),
  8 => 
  array (
    'name' => 'note',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'housekeeping_tasks',
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