<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\Refund;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RefundController extends BaseCrudController
{
    protected string $modelClass = Refund::class;
    protected string $route = 'admin.refunds';
    protected string $viewPath = 'admin.refunds';
    protected string $titleKey = 'refunds';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = ['guest', 'payment', 'invoice'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'refund_no' => 'required|string|max:50|unique:refunds,refund_no,' . ($model?->id ?? 'NULL'),
            'payment_id' => 'nullable|exists:payments,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'guest_id' => 'required|exists:guests,id',
            'amount' => 'required|numeric|min:0',
            'refunded_at' => 'required|date',
            'reason' => 'nullable|string',
            'status' => 'required|in:pending,completed,cancelled',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'refund_no'],
            ['data' => 'guest.full_name', 'titleKey' => 'guest'],
            ['data' => 'amount'],
            ['data' => 'refunded_at'],
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
    'name' => 'refund_no',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'payment_id',
    'i18n' => 'payment_no',
    'type' => 'select',
    'options' => \App\Models\Payment::pluck('payment_no', 'id')->toArray(),
  ),
  3 => 
  array (
    'name' => 'invoice_id',
    'i18n' => 'invoice_no',
    'type' => 'select',
    'options' => \App\Models\Invoice::pluck('invoice_no', 'id')->toArray(),
  ),
  4 => 
  array (
    'name' => 'guest_id',
    'i18n' => 'guest',
    'type' => 'select',
    'options' => \App\Models\Guest::pluck('full_name', 'id')->toArray(),
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
    'name' => 'refunded_at',
    'type' => 'datetime',
    'required' => true,
  ),
  7 => 
  array (
    'name' => 'reason',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
  8 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'pending' => 'Pending',
      'completed' => 'Completed',
      'cancelled' => 'Cancelled',
    ),
    'default' => 'pending',
  ),
),
            'titleKey' => 'refunds',
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
    'name' => 'refund_no',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'payment_id',
    'i18n' => 'payment_no',
    'type' => 'select',
    'options' => \App\Models\Payment::pluck('payment_no', 'id')->toArray(),
  ),
  3 => 
  array (
    'name' => 'invoice_id',
    'i18n' => 'invoice_no',
    'type' => 'select',
    'options' => \App\Models\Invoice::pluck('invoice_no', 'id')->toArray(),
  ),
  4 => 
  array (
    'name' => 'guest_id',
    'i18n' => 'guest',
    'type' => 'select',
    'options' => \App\Models\Guest::pluck('full_name', 'id')->toArray(),
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
    'name' => 'refunded_at',
    'type' => 'datetime',
    'required' => true,
  ),
  7 => 
  array (
    'name' => 'reason',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
  8 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'pending' => 'Pending',
      'completed' => 'Completed',
      'cancelled' => 'Cancelled',
    ),
    'default' => 'pending',
  ),
),
            'titleKey' => 'refunds',
        ];
    }

    protected function mutateData(Request $request, array $data, ?Model $model): array
    {
        return $data;
    }

    protected function afterStore(\Illuminate\Http\Request $request, \Illuminate\Database\Eloquent\Model $model): void
    {
        if (in_array('refunded_by', $model->getFillable(), true) || \Illuminate\Support\Facades\Schema::hasColumn($model->getTable(), 'refunded_by')) {
            $model->forceFill(['refunded_by' => auth()->id()])->save();
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