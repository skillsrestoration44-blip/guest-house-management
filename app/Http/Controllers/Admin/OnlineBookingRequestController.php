<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\OnlineBookingRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class OnlineBookingRequestController extends BaseCrudController
{
    protected string $modelClass = OnlineBookingRequest::class;
    protected string $route = 'admin.online_booking_requests';
    protected string $viewPath = 'admin.online_booking_requests';
    protected string $titleKey = 'online_booking_requests';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = ['roomType', 'paymentMethod'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'request_no' => 'required|string|max:50|unique:online_booking_requests,request_no,' . ($model?->id ?? 'NULL'),
            'guest_name' => 'required|string|max:150',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:150',
            'room_type_id' => 'nullable|exists:room_types,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after_or_equal:check_in_date',
            'total_guests' => 'required|integer|min:1',
            'deposit_amount' => 'nullable|numeric|min:0',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'payment_reference' => 'nullable|string|max:150',
            'status' => 'required|in:pending,approved,rejected,cancelled',
            'note' => 'nullable|string',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'request_no'],
            ['data' => 'guest_name', 'titleKey' => 'guest'],
            ['data' => 'phone'],
            ['data' => 'check_in_date'],
            ['data' => 'check_out_date'],
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
    'name' => 'request_no',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'guest_name',
    'i18n' => 'guest',
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'phone',
    'required' => true,
  ),
  4 => 
  array (
    'name' => 'email',
    'type' => 'email',
  ),
  5 => 
  array (
    'name' => 'room_type_id',
    'i18n' => 'room_type',
    'type' => 'select',
    'options' => \App\Models\RoomType::pluck('name', 'id')->toArray(),
  ),
  6 => 
  array (
    'name' => 'check_in_date',
    'type' => 'date',
    'required' => true,
  ),
  7 => 
  array (
    'name' => 'check_out_date',
    'type' => 'date',
    'required' => true,
  ),
  8 => 
  array (
    'name' => 'total_guests',
    'type' => 'number',
    'default' => 1,
  ),
  9 => 
  array (
    'name' => 'deposit_amount',
    'type' => 'number',
    'step' => '0.01',
  ),
  10 => 
  array (
    'name' => 'payment_method_id',
    'i18n' => 'payment_methods',
    'type' => 'select',
    'options' => \App\Models\PaymentMethod::pluck('name', 'id')->toArray(),
  ),
  11 => 
  array (
    'name' => 'payment_reference',
  ),
  12 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'pending' => 'Pending',
      'approved' => 'Approved',
      'rejected' => 'Rejected',
      'cancelled' => 'Cancelled',
    ),
    'default' => 'pending',
  ),
  13 => 
  array (
    'name' => 'note',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'online_booking_requests',
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
    'name' => 'guest_name',
    'i18n' => 'guest',
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'phone',
    'required' => true,
  ),
  4 => 
  array (
    'name' => 'email',
    'type' => 'email',
  ),
  5 => 
  array (
    'name' => 'room_type_id',
    'i18n' => 'room_type',
    'type' => 'select',
    'options' => \App\Models\RoomType::pluck('name', 'id')->toArray(),
  ),
  6 => 
  array (
    'name' => 'check_in_date',
    'type' => 'date',
    'required' => true,
  ),
  7 => 
  array (
    'name' => 'check_out_date',
    'type' => 'date',
    'required' => true,
  ),
  8 => 
  array (
    'name' => 'total_guests',
    'type' => 'number',
    'default' => 1,
  ),
  9 => 
  array (
    'name' => 'deposit_amount',
    'type' => 'number',
    'step' => '0.01',
  ),
  10 => 
  array (
    'name' => 'payment_method_id',
    'i18n' => 'payment_methods',
    'type' => 'select',
    'options' => \App\Models\PaymentMethod::pluck('name', 'id')->toArray(),
  ),
  11 => 
  array (
    'name' => 'payment_reference',
  ),
  12 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'pending' => 'Pending',
      'approved' => 'Approved',
      'rejected' => 'Rejected',
      'cancelled' => 'Cancelled',
    ),
    'default' => 'pending',
  ),
  13 => 
  array (
    'name' => 'note',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'online_booking_requests',
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