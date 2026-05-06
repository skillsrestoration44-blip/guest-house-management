<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\Salary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SalaryController extends BaseCrudController
{
    protected string $modelClass = Salary::class;
    protected string $route = 'admin.salaries';
    protected string $viewPath = 'admin.salaries';
    protected string $titleKey = 'salaries';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = ['staff', 'paymentMethod'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'staff_id' => 'required|exists:staff,id',
            'salary_month' => 'required|string|max:7',
            'basic_salary' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric',
            'deduction' => 'nullable|numeric',
            'net_salary' => 'required|numeric',
            'paid_at' => 'nullable|date',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'status' => 'required|in:pending,paid,cancelled',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'staff.full_name', 'titleKey' => 'staff'],
            ['data' => 'salary_month'],
            ['data' => 'basic_salary'],
            ['data' => 'net_salary'],
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
    'name' => 'staff_id',
    'i18n' => 'staff',
    'type' => 'select',
    'options' => \App\Models\Staff::pluck('full_name', 'id')->toArray(),
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'salary_month',
    'type' => 'month',
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'basic_salary',
    'type' => 'number',
    'step' => '0.01',
    'required' => true,
  ),
  4 => 
  array (
    'name' => 'bonus',
    'type' => 'number',
    'step' => '0.01',
  ),
  5 => 
  array (
    'name' => 'deduction',
    'type' => 'number',
    'step' => '0.01',
  ),
  6 => 
  array (
    'name' => 'net_salary',
    'type' => 'number',
    'step' => '0.01',
    'required' => true,
  ),
  7 => 
  array (
    'name' => 'paid_at',
    'type' => 'datetime',
  ),
  8 => 
  array (
    'name' => 'payment_method_id',
    'i18n' => 'payment_methods',
    'type' => 'select',
    'options' => \App\Models\PaymentMethod::pluck('name', 'id')->toArray(),
  ),
  9 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'pending' => 'Pending',
      'paid' => 'Paid',
      'cancelled' => 'Cancelled',
    ),
    'default' => 'pending',
  ),
),
            'titleKey' => 'salaries',
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
    'name' => 'staff_id',
    'i18n' => 'staff',
    'type' => 'select',
    'options' => \App\Models\Staff::pluck('full_name', 'id')->toArray(),
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'salary_month',
    'type' => 'month',
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'basic_salary',
    'type' => 'number',
    'step' => '0.01',
    'required' => true,
  ),
  4 => 
  array (
    'name' => 'bonus',
    'type' => 'number',
    'step' => '0.01',
  ),
  5 => 
  array (
    'name' => 'deduction',
    'type' => 'number',
    'step' => '0.01',
  ),
  6 => 
  array (
    'name' => 'net_salary',
    'type' => 'number',
    'step' => '0.01',
    'required' => true,
  ),
  7 => 
  array (
    'name' => 'paid_at',
    'type' => 'datetime',
  ),
  8 => 
  array (
    'name' => 'payment_method_id',
    'i18n' => 'payment_methods',
    'type' => 'select',
    'options' => \App\Models\PaymentMethod::pluck('name', 'id')->toArray(),
  ),
  9 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'pending' => 'Pending',
      'paid' => 'Paid',
      'cancelled' => 'Cancelled',
    ),
    'default' => 'pending',
  ),
),
            'titleKey' => 'salaries',
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