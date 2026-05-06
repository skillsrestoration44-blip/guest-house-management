<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\StaffAttendance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class StaffAttendanceController extends BaseCrudController
{
    protected string $modelClass = StaffAttendance::class;
    protected string $route = 'admin.staff_attendances';
    protected string $viewPath = 'admin.staff_attendances';
    protected string $titleKey = 'staff_attendance';
    protected bool $hasBranchScope = false;
    protected array $eagerLoad = ['staff'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'staff_id' => 'required|exists:staff,id',
            'attendance_date' => 'required|date',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,late,leave',
            'note' => 'nullable|string',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'staff.full_name', 'titleKey' => 'staff'],
            ['data' => 'attendance_date'],
            ['data' => 'check_in_time'],
            ['data' => 'check_out_time'],
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
    'name' => 'staff_id',
    'i18n' => 'staff',
    'type' => 'select',
    'options' => \App\Models\Staff::pluck('full_name', 'id')->toArray(),
    'required' => true,
  ),
  1 => 
  array (
    'name' => 'attendance_date',
    'type' => 'date',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'check_in_time',
    'type' => 'time',
  ),
  3 => 
  array (
    'name' => 'check_out_time',
    'type' => 'time',
  ),
  4 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'present' => 'Present',
      'absent' => 'Absent',
      'late' => 'Late',
      'leave' => 'Leave',
    ),
    'default' => 'present',
  ),
  5 => 
  array (
    'name' => 'note',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'staff_attendance',
        ];
    }

    protected function showViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'staff_id',
    'i18n' => 'staff',
    'type' => 'select',
    'options' => \App\Models\Staff::pluck('full_name', 'id')->toArray(),
    'required' => true,
  ),
  1 => 
  array (
    'name' => 'attendance_date',
    'type' => 'date',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'check_in_time',
    'type' => 'time',
  ),
  3 => 
  array (
    'name' => 'check_out_time',
    'type' => 'time',
  ),
  4 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'present' => 'Present',
      'absent' => 'Absent',
      'late' => 'Late',
      'leave' => 'Leave',
    ),
    'default' => 'present',
  ),
  5 => 
  array (
    'name' => 'note',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'staff_attendance',
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