<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\Expense;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ExpenseController extends BaseCrudController
{
    protected string $modelClass = Expense::class;
    protected string $route = 'admin.expenses';
    protected string $viewPath = 'admin.expenses';
    protected string $titleKey = 'expenses';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = ['category', 'paymentMethod'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'expense_no' => 'required|string|max:50|unique:expenses,expense_no,' . ($model?->id ?? 'NULL'),
            'expense_category_id' => 'required|exists:expense_categories,id',
            'expense_date' => 'required|date',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'reference_no' => 'nullable|string|max:150',
            'attachment' => 'nullable|file|max:8192',
            'status' => 'required|in:pending,approved,rejected,paid',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'expense_no'],
            ['data' => 'category.name', 'titleKey' => 'expense_categories'],
            ['data' => 'expense_date'],
            ['data' => 'amount'],
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
    'name' => 'expense_no',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'expense_category_id',
    'i18n' => 'expense_categories',
    'type' => 'select',
    'options' => \App\Models\ExpenseCategory::pluck('name', 'id')->toArray(),
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'expense_date',
    'type' => 'date',
    'required' => true,
  ),
  4 => 
  array (
    'name' => 'description',
    'type' => 'textarea',
    'col' => 'col-12',
    'required' => true,
  ),
  5 => 
  array (
    'name' => 'amount',
    'type' => 'number',
    'step' => '0.01',
    'required' => true,
  ),
  6 => 
  array (
    'name' => 'payment_method_id',
    'i18n' => 'payment_methods',
    'type' => 'select',
    'options' => \App\Models\PaymentMethod::pluck('name', 'id')->toArray(),
  ),
  7 => 
  array (
    'name' => 'reference_no',
  ),
  8 => 
  array (
    'name' => 'attachment',
    'type' => 'file',
  ),
  9 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'pending' => 'Pending',
      'approved' => 'Approved',
      'rejected' => 'Rejected',
      'paid' => 'Paid',
    ),
    'default' => 'pending',
  ),
),
            'titleKey' => 'expenses',
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
    'name' => 'expense_no',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'expense_category_id',
    'i18n' => 'expense_categories',
    'type' => 'select',
    'options' => \App\Models\ExpenseCategory::pluck('name', 'id')->toArray(),
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'expense_date',
    'type' => 'date',
    'required' => true,
  ),
  4 => 
  array (
    'name' => 'description',
    'type' => 'textarea',
    'col' => 'col-12',
    'required' => true,
  ),
  5 => 
  array (
    'name' => 'amount',
    'type' => 'number',
    'step' => '0.01',
    'required' => true,
  ),
  6 => 
  array (
    'name' => 'payment_method_id',
    'i18n' => 'payment_methods',
    'type' => 'select',
    'options' => \App\Models\PaymentMethod::pluck('name', 'id')->toArray(),
  ),
  7 => 
  array (
    'name' => 'reference_no',
  ),
  8 => 
  array (
    'name' => 'attachment',
    'type' => 'file',
  ),
  9 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'pending' => 'Pending',
      'approved' => 'Approved',
      'rejected' => 'Rejected',
      'paid' => 'Paid',
    ),
    'default' => 'pending',
  ),
),
            'titleKey' => 'expenses',
        ];
    }

    protected function mutateData(Request $request, array $data, ?Model $model): array
    {
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('expenses', 'public');
        } else {
            unset($data['attachment']);
        }
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