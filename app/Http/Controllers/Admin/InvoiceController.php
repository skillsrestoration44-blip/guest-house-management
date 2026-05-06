<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class InvoiceController extends BaseCrudController
{
    protected string $modelClass = Invoice::class;
    protected string $route = 'admin.invoices';
    protected string $viewPath = 'admin.invoices';
    protected string $titleKey = 'invoices';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = ['guest', 'booking'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'invoice_no' => 'required|string|max:50|unique:invoices,invoice_no,' . ($model?->id ?? 'NULL'),
            'booking_id' => 'nullable|exists:bookings,id',
            'stay_id' => 'nullable|exists:stays,id',
            'guest_id' => 'required|exists:guests,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'room_total' => 'nullable|numeric|min:0',
            'service_total' => 'nullable|numeric|min:0',
            'damage_total' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'grand_total' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'balance_due' => 'nullable|numeric',
            'status' => 'required|in:draft,unpaid,partial,paid,cancelled,refunded',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'invoice_no'],
            ['data' => 'guest.full_name', 'titleKey' => 'guest'],
            ['data' => 'invoice_date'],
            ['data' => 'grand_total'],
            ['data' => 'paid_amount'],
            ['data' => 'balance_due'],
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
    'name' => 'invoice_no',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'booking_id',
    'i18n' => 'booking',
    'type' => 'select',
    'options' => \App\Models\Booking::pluck('booking_no', 'id')->toArray(),
  ),
  3 => 
  array (
    'name' => 'stay_id',
    'i18n' => 'stay_no',
    'type' => 'select',
    'options' => \App\Models\Stay::pluck('stay_no', 'id')->toArray(),
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
    'name' => 'invoice_date',
    'type' => 'date',
    'required' => true,
  ),
  6 => 
  array (
    'name' => 'due_date',
    'type' => 'date',
  ),
  7 => 
  array (
    'name' => 'room_total',
    'type' => 'number',
    'step' => '0.01',
  ),
  8 => 
  array (
    'name' => 'service_total',
    'type' => 'number',
    'step' => '0.01',
  ),
  9 => 
  array (
    'name' => 'damage_total',
    'type' => 'number',
    'step' => '0.01',
  ),
  10 => 
  array (
    'name' => 'discount_amount',
    'type' => 'number',
    'step' => '0.01',
  ),
  11 => 
  array (
    'name' => 'tax_amount',
    'type' => 'number',
    'step' => '0.01',
  ),
  12 => 
  array (
    'name' => 'deposit_amount',
    'type' => 'number',
    'step' => '0.01',
  ),
  13 => 
  array (
    'name' => 'grand_total',
    'type' => 'number',
    'step' => '0.01',
  ),
  14 => 
  array (
    'name' => 'paid_amount',
    'type' => 'number',
    'step' => '0.01',
  ),
  15 => 
  array (
    'name' => 'balance_due',
    'type' => 'number',
    'step' => '0.01',
  ),
  16 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'draft' => 'Draft',
      'unpaid' => 'Unpaid',
      'partial' => 'Partial',
      'paid' => 'Paid',
      'cancelled' => 'Cancelled',
      'refunded' => 'Refunded',
    ),
    'default' => 'draft',
  ),
),
            'titleKey' => 'invoices',
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
    'name' => 'invoice_no',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'booking_id',
    'i18n' => 'booking',
    'type' => 'select',
    'options' => \App\Models\Booking::pluck('booking_no', 'id')->toArray(),
  ),
  3 => 
  array (
    'name' => 'stay_id',
    'i18n' => 'stay_no',
    'type' => 'select',
    'options' => \App\Models\Stay::pluck('stay_no', 'id')->toArray(),
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
    'name' => 'invoice_date',
    'type' => 'date',
    'required' => true,
  ),
  6 => 
  array (
    'name' => 'due_date',
    'type' => 'date',
  ),
  7 => 
  array (
    'name' => 'room_total',
    'type' => 'number',
    'step' => '0.01',
  ),
  8 => 
  array (
    'name' => 'service_total',
    'type' => 'number',
    'step' => '0.01',
  ),
  9 => 
  array (
    'name' => 'damage_total',
    'type' => 'number',
    'step' => '0.01',
  ),
  10 => 
  array (
    'name' => 'discount_amount',
    'type' => 'number',
    'step' => '0.01',
  ),
  11 => 
  array (
    'name' => 'tax_amount',
    'type' => 'number',
    'step' => '0.01',
  ),
  12 => 
  array (
    'name' => 'deposit_amount',
    'type' => 'number',
    'step' => '0.01',
  ),
  13 => 
  array (
    'name' => 'grand_total',
    'type' => 'number',
    'step' => '0.01',
  ),
  14 => 
  array (
    'name' => 'paid_amount',
    'type' => 'number',
    'step' => '0.01',
  ),
  15 => 
  array (
    'name' => 'balance_due',
    'type' => 'number',
    'step' => '0.01',
  ),
  16 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'draft' => 'Draft',
      'unpaid' => 'Unpaid',
      'partial' => 'Partial',
      'paid' => 'Paid',
      'cancelled' => 'Cancelled',
      'refunded' => 'Refunded',
    ),
    'default' => 'draft',
  ),
),
            'titleKey' => 'invoices',
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