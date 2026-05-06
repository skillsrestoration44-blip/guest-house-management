<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\ServiceCharge;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ServiceChargeController extends BaseCrudController
{
    protected string $modelClass = ServiceCharge::class;
    protected string $route = 'admin.service_charges';
    protected string $viewPath = 'admin.service_charges';
    protected string $titleKey = 'service_charges';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = ['service', 'guest', 'room'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'service_id' => 'required|exists:services,id',
            'guest_id' => 'required|exists:guests,id',
            'stay_id' => 'nullable|exists:stays,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'room_id' => 'nullable|exists:rooms,id',
            'charge_date' => 'required|date',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'total' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,billed,cancelled',
            'note' => 'nullable|string',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'service.name', 'titleKey' => 'services'],
            ['data' => 'guest.full_name', 'titleKey' => 'guest'],
            ['data' => 'room.room_number', 'titleKey' => 'room'],
            ['data' => 'charge_date'],
            ['data' => 'quantity'],
            ['data' => 'total'],
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
    'name' => 'service_id',
    'i18n' => 'services',
    'type' => 'select',
    'options' => \App\Models\Service::pluck('name', 'id')->toArray(),
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'guest_id',
    'i18n' => 'guest',
    'type' => 'select',
    'options' => \App\Models\Guest::pluck('full_name', 'id')->toArray(),
    'required' => true,
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
    'name' => 'booking_id',
    'i18n' => 'booking',
    'type' => 'select',
    'options' => \App\Models\Booking::pluck('booking_no', 'id')->toArray(),
  ),
  5 => 
  array (
    'name' => 'room_id',
    'i18n' => 'room',
    'type' => 'select',
    'options' => \App\Models\Room::pluck('room_number', 'id')->toArray(),
  ),
  6 => 
  array (
    'name' => 'charge_date',
    'type' => 'date',
    'required' => true,
  ),
  7 => 
  array (
    'name' => 'quantity',
    'type' => 'number',
    'step' => '0.01',
    'default' => 1,
    'required' => true,
  ),
  8 => 
  array (
    'name' => 'unit_price',
    'type' => 'number',
    'step' => '0.01',
    'required' => true,
  ),
  9 => 
  array (
    'name' => 'total',
    'type' => 'number',
    'step' => '0.01',
  ),
  10 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'pending' => 'Pending',
      'billed' => 'Billed',
      'cancelled' => 'Cancelled',
    ),
    'default' => 'pending',
  ),
  11 => 
  array (
    'name' => 'note',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'service_charges',
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
    'name' => 'service_id',
    'i18n' => 'services',
    'type' => 'select',
    'options' => \App\Models\Service::pluck('name', 'id')->toArray(),
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'guest_id',
    'i18n' => 'guest',
    'type' => 'select',
    'options' => \App\Models\Guest::pluck('full_name', 'id')->toArray(),
    'required' => true,
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
    'name' => 'booking_id',
    'i18n' => 'booking',
    'type' => 'select',
    'options' => \App\Models\Booking::pluck('booking_no', 'id')->toArray(),
  ),
  5 => 
  array (
    'name' => 'room_id',
    'i18n' => 'room',
    'type' => 'select',
    'options' => \App\Models\Room::pluck('room_number', 'id')->toArray(),
  ),
  6 => 
  array (
    'name' => 'charge_date',
    'type' => 'date',
    'required' => true,
  ),
  7 => 
  array (
    'name' => 'quantity',
    'type' => 'number',
    'step' => '0.01',
    'default' => 1,
    'required' => true,
  ),
  8 => 
  array (
    'name' => 'unit_price',
    'type' => 'number',
    'step' => '0.01',
    'required' => true,
  ),
  9 => 
  array (
    'name' => 'total',
    'type' => 'number',
    'step' => '0.01',
  ),
  10 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'pending' => 'Pending',
      'billed' => 'Billed',
      'cancelled' => 'Cancelled',
    ),
    'default' => 'pending',
  ),
  11 => 
  array (
    'name' => 'note',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'service_charges',
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