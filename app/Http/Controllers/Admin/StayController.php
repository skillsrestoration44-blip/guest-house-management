<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\Stay;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class StayController extends BaseCrudController
{
    protected string $modelClass = Stay::class;
    protected string $route = 'admin.stays';
    protected string $viewPath = 'admin.stays';
    protected string $titleKey = 'stays';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = ['guest', 'room'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'stay_no' => 'required|string|max:50|unique:stays,stay_no,' . ($model?->id ?? 'NULL'),
            'booking_id' => 'nullable|exists:bookings,id',
            'guest_id' => 'required|exists:guests,id',
            'room_id' => 'required|exists:rooms,id',
            'actual_check_in_at' => 'required|date',
            'expected_check_out_at' => 'required|date',
            'actual_check_out_at' => 'nullable|date',
            'room_price' => 'nullable|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'damage_fee' => 'nullable|numeric|min:0',
            'late_checkout_fee' => 'nullable|numeric|min:0',
            'status' => 'required|in:checked_in,checked_out,transferred,cancelled',
            'note' => 'nullable|string',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'stay_no'],
            ['data' => 'guest.full_name', 'titleKey' => 'guest'],
            ['data' => 'room.room_number', 'titleKey' => 'room'],
            ['data' => 'actual_check_in_at'],
            ['data' => 'expected_check_out_at'],
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
    'name' => 'stay_no',
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
    'name' => 'guest_id',
    'i18n' => 'guest',
    'type' => 'select',
    'options' => \App\Models\Guest::pluck('full_name', 'id')->toArray(),
    'required' => true,
  ),
  4 => 
  array (
    'name' => 'room_id',
    'i18n' => 'room',
    'type' => 'select',
    'options' => \App\Models\Room::pluck('room_number', 'id')->toArray(),
    'required' => true,
  ),
  5 => 
  array (
    'name' => 'actual_check_in_at',
    'i18n' => 'check_in',
    'type' => 'datetime',
    'required' => true,
  ),
  6 => 
  array (
    'name' => 'expected_check_out_at',
    'type' => 'datetime',
    'required' => true,
  ),
  7 => 
  array (
    'name' => 'actual_check_out_at',
    'i18n' => 'check_out',
    'type' => 'datetime',
  ),
  8 => 
  array (
    'name' => 'room_price',
    'type' => 'number',
    'step' => '0.01',
  ),
  9 => 
  array (
    'name' => 'deposit_amount',
    'type' => 'number',
    'step' => '0.01',
  ),
  10 => 
  array (
    'name' => 'damage_fee',
    'type' => 'number',
    'step' => '0.01',
  ),
  11 => 
  array (
    'name' => 'late_checkout_fee',
    'type' => 'number',
    'step' => '0.01',
  ),
  12 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'checked_in' => 'Checked In',
      'checked_out' => 'Checked Out',
      'transferred' => 'Transferred',
      'cancelled' => 'Cancelled',
    ),
    'default' => 'checked_in',
  ),
  13 => 
  array (
    'name' => 'note',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'stays',
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
    'name' => 'stay_no',
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
    'name' => 'guest_id',
    'i18n' => 'guest',
    'type' => 'select',
    'options' => \App\Models\Guest::pluck('full_name', 'id')->toArray(),
    'required' => true,
  ),
  4 => 
  array (
    'name' => 'room_id',
    'i18n' => 'room',
    'type' => 'select',
    'options' => \App\Models\Room::pluck('room_number', 'id')->toArray(),
    'required' => true,
  ),
  5 => 
  array (
    'name' => 'actual_check_in_at',
    'i18n' => 'check_in',
    'type' => 'datetime',
    'required' => true,
  ),
  6 => 
  array (
    'name' => 'expected_check_out_at',
    'type' => 'datetime',
    'required' => true,
  ),
  7 => 
  array (
    'name' => 'actual_check_out_at',
    'i18n' => 'check_out',
    'type' => 'datetime',
  ),
  8 => 
  array (
    'name' => 'room_price',
    'type' => 'number',
    'step' => '0.01',
  ),
  9 => 
  array (
    'name' => 'deposit_amount',
    'type' => 'number',
    'step' => '0.01',
  ),
  10 => 
  array (
    'name' => 'damage_fee',
    'type' => 'number',
    'step' => '0.01',
  ),
  11 => 
  array (
    'name' => 'late_checkout_fee',
    'type' => 'number',
    'step' => '0.01',
  ),
  12 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'checked_in' => 'Checked In',
      'checked_out' => 'Checked Out',
      'transferred' => 'Transferred',
      'cancelled' => 'Cancelled',
    ),
    'default' => 'checked_in',
  ),
  13 => 
  array (
    'name' => 'note',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'stays',
        ];
    }

    protected function mutateData(Request $request, array $data, ?Model $model): array
    {
        return $data;
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