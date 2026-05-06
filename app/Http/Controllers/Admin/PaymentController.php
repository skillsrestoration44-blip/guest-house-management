<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PaymentController extends BaseCrudController
{
    protected string $modelClass = Payment::class;
    protected string $route = 'admin.payments';
    protected string $viewPath = 'admin.payments';
    protected string $titleKey = 'payments';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = ['guest', 'paymentMethod', 'invoice'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'payment_no' => 'required|string|max:50|unique:payments,payment_no,' . ($model?->id ?? 'NULL'),
            'invoice_id' => 'nullable|exists:invoices,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'stay_id' => 'nullable|exists:stays,id',
            'guest_id' => 'required|exists:guests,id',
            'payment_date' => 'required|date',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'payment_type' => 'required|in:deposit,room_fee,service_fee,full,partial,refund',
            'amount' => 'required|numeric|min:0',
            'reference_no' => 'nullable|string|max:150',
            'status' => 'required|in:pending,completed,failed,cancelled,refunded',
            'note' => 'nullable|string',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'payment_no'],
            ['data' => 'guest.full_name', 'titleKey' => 'guest'],
            ['data' => 'payment_date'],
            ['data' => 'payment_method.name', 'titleKey' => 'payment_methods'],
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
    'name' => 'payment_no',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'invoice_id',
    'i18n' => 'invoice_no',
    'type' => 'select',
    'options' => \App\Models\Invoice::pluck('invoice_no', 'id')->toArray(),
  ),
  3 => 
  array (
    'name' => 'booking_id',
    'i18n' => 'booking',
    'type' => 'select',
    'options' => \App\Models\Booking::pluck('booking_no', 'id')->toArray(),
  ),
  4 => 
  array (
    'name' => 'stay_id',
    'i18n' => 'stay_no',
    'type' => 'select',
    'options' => \App\Models\Stay::pluck('stay_no', 'id')->toArray(),
  ),
  5 => 
  array (
    'name' => 'guest_id',
    'i18n' => 'guest',
    'type' => 'select',
    'options' => \App\Models\Guest::pluck('full_name', 'id')->toArray(),
    'required' => true,
  ),
  6 => 
  array (
    'name' => 'payment_date',
    'type' => 'datetime',
    'required' => true,
  ),
  7 => 
  array (
    'name' => 'payment_method_id',
    'i18n' => 'payment_methods',
    'type' => 'select',
    'options' => \App\Models\PaymentMethod::pluck('name', 'id')->toArray(),
    'required' => true,
  ),
  8 => 
  array (
    'name' => 'payment_type',
    'type' => 'select',
    'options' => 
    array (
      'deposit' => 'Deposit',
      'room_fee' => 'Room Fee',
      'service_fee' => 'Service Fee',
      'full' => 'Full',
      'partial' => 'Partial',
      'refund' => 'Refund',
    ),
    'required' => true,
  ),
  9 => 
  array (
    'name' => 'amount',
    'type' => 'number',
    'step' => '0.01',
    'required' => true,
  ),
  10 => 
  array (
    'name' => 'reference_no',
  ),
  11 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'pending' => 'Pending',
      'completed' => 'Completed',
      'failed' => 'Failed',
      'cancelled' => 'Cancelled',
      'refunded' => 'Refunded',
    ),
    'default' => 'completed',
  ),
  12 => 
  array (
    'name' => 'note',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'payments',
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
    'name' => 'payment_no',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'invoice_id',
    'i18n' => 'invoice_no',
    'type' => 'select',
    'options' => \App\Models\Invoice::pluck('invoice_no', 'id')->toArray(),
  ),
  3 => 
  array (
    'name' => 'booking_id',
    'i18n' => 'booking',
    'type' => 'select',
    'options' => \App\Models\Booking::pluck('booking_no', 'id')->toArray(),
  ),
  4 => 
  array (
    'name' => 'stay_id',
    'i18n' => 'stay_no',
    'type' => 'select',
    'options' => \App\Models\Stay::pluck('stay_no', 'id')->toArray(),
  ),
  5 => 
  array (
    'name' => 'guest_id',
    'i18n' => 'guest',
    'type' => 'select',
    'options' => \App\Models\Guest::pluck('full_name', 'id')->toArray(),
    'required' => true,
  ),
  6 => 
  array (
    'name' => 'payment_date',
    'type' => 'datetime',
    'required' => true,
  ),
  7 => 
  array (
    'name' => 'payment_method_id',
    'i18n' => 'payment_methods',
    'type' => 'select',
    'options' => \App\Models\PaymentMethod::pluck('name', 'id')->toArray(),
    'required' => true,
  ),
  8 => 
  array (
    'name' => 'payment_type',
    'type' => 'select',
    'options' => 
    array (
      'deposit' => 'Deposit',
      'room_fee' => 'Room Fee',
      'service_fee' => 'Service Fee',
      'full' => 'Full',
      'partial' => 'Partial',
      'refund' => 'Refund',
    ),
    'required' => true,
  ),
  9 => 
  array (
    'name' => 'amount',
    'type' => 'number',
    'step' => '0.01',
    'required' => true,
  ),
  10 => 
  array (
    'name' => 'reference_no',
  ),
  11 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'pending' => 'Pending',
      'completed' => 'Completed',
      'failed' => 'Failed',
      'cancelled' => 'Cancelled',
      'refunded' => 'Refunded',
    ),
    'default' => 'completed',
  ),
  12 => 
  array (
    'name' => 'note',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'payments',
        ];
    }

    protected function mutateData(Request $request, array $data, ?Model $model): array
    {
        return $data;
    }

    protected function afterStore(\Illuminate\Http\Request $request, \Illuminate\Database\Eloquent\Model $model): void
    {
        if (in_array('received_by', $model->getFillable(), true) || \Illuminate\Support\Facades\Schema::hasColumn($model->getTable(), 'received_by')) {
            $model->forceFill(['received_by' => auth()->id()])->save();
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