<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BookingController extends BaseCrudController
{
    protected string $modelClass = Booking::class;
    protected string $route = 'admin.bookings';
    protected string $viewPath = 'admin.bookings';
    protected string $titleKey = 'bookings';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = ['guest', 'room'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'booking_no' => 'required|string|max:50|unique:bookings,booking_no,' . ($model?->id ?? 'NULL'),
            'guest_id' => 'required|exists:guests,id',
            'room_id' => 'required|exists:rooms,id',
            'booking_source' => 'required|in:walk_in,phone,website,facebook,agency',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after_or_equal:check_in_date',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'adults' => 'required|integer|min:0',
            'children' => 'required|integer|min:0',
            'total_guests' => 'required|integer|min:1',
            'room_price' => 'nullable|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,confirmed,checked_in,checked_out,cancelled,no_show',
            'note' => 'nullable|string',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'booking_no'],
            ['data' => 'guest.full_name', 'titleKey' => 'guest'],
            ['data' => 'room.room_number', 'titleKey' => 'room'],
            ['data' => 'check_in_date'],
            ['data' => 'check_out_date'],
            ['data' => 'total_guests'],
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
    'name' => 'booking_no',
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
    'name' => 'room_id',
    'i18n' => 'room',
    'type' => 'select',
    'options' => \App\Models\Room::pluck('room_number', 'id')->toArray(),
    'required' => true,
  ),
  4 => 
  array (
    'name' => 'booking_source',
    'type' => 'select',
    'options' => 
    array (
      'walk_in' => 'Walk In',
      'phone' => 'Phone',
      'website' => 'Website',
      'facebook' => 'Facebook',
      'agency' => 'Agency',
    ),
    'default' => 'walk_in',
  ),
  5 => 
  array (
    'name' => 'check_in_date',
    'type' => 'date',
    'required' => true,
  ),
  6 => 
  array (
    'name' => 'check_out_date',
    'type' => 'date',
    'required' => true,
  ),
  7 => 
  array (
    'name' => 'check_in_time',
    'type' => 'time',
  ),
  8 => 
  array (
    'name' => 'check_out_time',
    'type' => 'time',
  ),
  9 => 
  array (
    'name' => 'adults',
    'type' => 'number',
    'default' => 1,
  ),
  10 => 
  array (
    'name' => 'children',
    'type' => 'number',
    'default' => 0,
  ),
  11 => 
  array (
    'name' => 'total_guests',
    'type' => 'number',
    'default' => 1,
  ),
  12 => 
  array (
    'name' => 'room_price',
    'type' => 'number',
    'step' => '0.01',
  ),
  13 => 
  array (
    'name' => 'deposit_amount',
    'type' => 'number',
    'step' => '0.01',
  ),
  14 => 
  array (
    'name' => 'discount_amount',
    'type' => 'number',
    'step' => '0.01',
  ),
  15 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'pending' => 'Pending',
      'confirmed' => 'Confirmed',
      'checked_in' => 'Checked In',
      'checked_out' => 'Checked Out',
      'cancelled' => 'Cancelled',
      'no_show' => 'No Show',
    ),
    'default' => 'pending',
  ),
  16 => 
  array (
    'name' => 'note',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'bookings',
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
    'name' => 'booking_no',
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
    'name' => 'room_id',
    'i18n' => 'room',
    'type' => 'select',
    'options' => \App\Models\Room::pluck('room_number', 'id')->toArray(),
    'required' => true,
  ),
  4 => 
  array (
    'name' => 'booking_source',
    'type' => 'select',
    'options' => 
    array (
      'walk_in' => 'Walk In',
      'phone' => 'Phone',
      'website' => 'Website',
      'facebook' => 'Facebook',
      'agency' => 'Agency',
    ),
    'default' => 'walk_in',
  ),
  5 => 
  array (
    'name' => 'check_in_date',
    'type' => 'date',
    'required' => true,
  ),
  6 => 
  array (
    'name' => 'check_out_date',
    'type' => 'date',
    'required' => true,
  ),
  7 => 
  array (
    'name' => 'check_in_time',
    'type' => 'time',
  ),
  8 => 
  array (
    'name' => 'check_out_time',
    'type' => 'time',
  ),
  9 => 
  array (
    'name' => 'adults',
    'type' => 'number',
    'default' => 1,
  ),
  10 => 
  array (
    'name' => 'children',
    'type' => 'number',
    'default' => 0,
  ),
  11 => 
  array (
    'name' => 'total_guests',
    'type' => 'number',
    'default' => 1,
  ),
  12 => 
  array (
    'name' => 'room_price',
    'type' => 'number',
    'step' => '0.01',
  ),
  13 => 
  array (
    'name' => 'deposit_amount',
    'type' => 'number',
    'step' => '0.01',
  ),
  14 => 
  array (
    'name' => 'discount_amount',
    'type' => 'number',
    'step' => '0.01',
  ),
  15 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'pending' => 'Pending',
      'confirmed' => 'Confirmed',
      'checked_in' => 'Checked In',
      'checked_out' => 'Checked Out',
      'cancelled' => 'Cancelled',
      'no_show' => 'No Show',
    ),
    'default' => 'pending',
  ),
  16 => 
  array (
    'name' => 'note',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'bookings',
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