<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\MaintenanceRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MaintenanceRequestController extends BaseCrudController
{
    protected string $modelClass = MaintenanceRequest::class;
    protected string $route = 'admin.maintenance_requests';
    protected string $viewPath = 'admin.maintenance_requests';
    protected string $titleKey = 'maintenance_requests';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = ['room', 'assignee'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'request_no' => 'required|string|max:50|unique:maintenance_requests,request_no,' . ($model?->id ?? 'NULL'),
            'room_id' => 'required|exists:rooms,id',
            'assigned_to' => 'nullable|exists:staff,id',
            'issue_type' => 'required|string|max:150',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_progress,waiting_material,completed,cancelled',
            'reported_at' => 'required|date',
            'started_at' => 'nullable|date',
            'completed_at' => 'nullable|date',
            'note' => 'nullable|string',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'request_no'],
            ['data' => 'room.room_number', 'titleKey' => 'room'],
            ['data' => 'issue_type'],
            ['data' => 'priority'],
            ['data' => 'status'],
            ['data' => 'reported_at'],
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
    'name' => 'request_no',
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
    'name' => 'issue_type',
    'required' => true,
  ),
  5 => 
  array (
    'name' => 'description',
    'type' => 'textarea',
    'col' => 'col-12',
    'required' => true,
  ),
  6 => 
  array (
    'name' => 'priority',
    'type' => 'select',
    'options' => 
    array (
      'low' => 'Low',
      'medium' => 'Medium',
      'high' => 'High',
      'urgent' => 'Urgent',
    ),
    'default' => 'medium',
  ),
  7 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'pending' => 'Pending',
      'in_progress' => 'In Progress',
      'waiting_material' => 'Waiting Material',
      'completed' => 'Completed',
      'cancelled' => 'Cancelled',
    ),
    'default' => 'pending',
  ),
  8 => 
  array (
    'name' => 'reported_at',
    'type' => 'datetime',
    'required' => true,
  ),
  9 => 
  array (
    'name' => 'started_at',
    'type' => 'datetime',
  ),
  10 => 
  array (
    'name' => 'completed_at',
    'type' => 'datetime',
  ),
  11 => 
  array (
    'name' => 'note',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'maintenance_requests',
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
    'name' => 'request_no',
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
    'name' => 'issue_type',
    'required' => true,
  ),
  5 => 
  array (
    'name' => 'description',
    'type' => 'textarea',
    'col' => 'col-12',
    'required' => true,
  ),
  6 => 
  array (
    'name' => 'priority',
    'type' => 'select',
    'options' => 
    array (
      'low' => 'Low',
      'medium' => 'Medium',
      'high' => 'High',
      'urgent' => 'Urgent',
    ),
    'default' => 'medium',
  ),
  7 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'pending' => 'Pending',
      'in_progress' => 'In Progress',
      'waiting_material' => 'Waiting Material',
      'completed' => 'Completed',
      'cancelled' => 'Cancelled',
    ),
    'default' => 'pending',
  ),
  8 => 
  array (
    'name' => 'reported_at',
    'type' => 'datetime',
    'required' => true,
  ),
  9 => 
  array (
    'name' => 'started_at',
    'type' => 'datetime',
  ),
  10 => 
  array (
    'name' => 'completed_at',
    'type' => 'datetime',
  ),
  11 => 
  array (
    'name' => 'note',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'maintenance_requests',
        ];
    }

    protected function mutateData(Request $request, array $data, ?Model $model): array
    {
        return $data;
    }

    protected function afterStore(\Illuminate\Http\Request $request, \Illuminate\Database\Eloquent\Model $model): void
    {
        if (in_array('reported_by', $model->getFillable(), true) || \Illuminate\Support\Facades\Schema::hasColumn($model->getTable(), 'reported_by')) {
            $model->forceFill(['reported_by' => auth()->id()])->save();
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