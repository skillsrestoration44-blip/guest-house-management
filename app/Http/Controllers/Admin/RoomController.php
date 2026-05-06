<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\Room;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RoomController extends BaseCrudController
{
    protected string $modelClass = Room::class;
    protected string $route = 'admin.rooms';
    protected string $viewPath = 'admin.rooms';
    protected string $titleKey = 'rooms';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = ['roomType'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'room_type_id' => 'required|exists:room_types,id',
            'room_number' => 'required|string|max:50|unique:rooms,room_number,' . ($model?->id ?? 'NULL'),
            'floor' => 'nullable|string|max:50',
            'bed_count' => 'required|integer|min:1',
            'max_guests' => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'price_per_hour' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,booked,occupied,cleaning,maintenance,blocked',
            'description' => 'nullable|string',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'room_number'],
            ['data' => 'room_type.name', 'titleKey' => 'room_type'],
            ['data' => 'floor'],
            ['data' => 'price_per_night'],
            ['data' => 'max_guests'],
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
    'name' => 'room_type_id',
    'i18n' => 'room_type',
    'type' => 'select',
    'options' => \App\Models\RoomType::pluck('name', 'id')->toArray(),
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'room_number',
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'floor',
  ),
  4 => 
  array (
    'name' => 'bed_count',
    'type' => 'number',
    'default' => 1,
  ),
  5 => 
  array (
    'name' => 'max_guests',
    'type' => 'number',
    'default' => 1,
  ),
  6 => 
  array (
    'name' => 'price_per_night',
    'type' => 'number',
    'step' => '0.01',
    'required' => true,
  ),
  7 => 
  array (
    'name' => 'price_per_hour',
    'type' => 'number',
    'step' => '0.01',
  ),
  8 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'available' => 'Available',
      'booked' => 'Booked',
      'occupied' => 'Occupied',
      'cleaning' => 'Cleaning',
      'maintenance' => 'Maintenance',
      'blocked' => 'Blocked',
    ),
    'default' => 'available',
  ),
  9 => 
  array (
    'name' => 'description',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'rooms',
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
    'name' => 'room_type_id',
    'i18n' => 'room_type',
    'type' => 'select',
    'options' => \App\Models\RoomType::pluck('name', 'id')->toArray(),
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'room_number',
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'floor',
  ),
  4 => 
  array (
    'name' => 'bed_count',
    'type' => 'number',
    'default' => 1,
  ),
  5 => 
  array (
    'name' => 'max_guests',
    'type' => 'number',
    'default' => 1,
  ),
  6 => 
  array (
    'name' => 'price_per_night',
    'type' => 'number',
    'step' => '0.01',
    'required' => true,
  ),
  7 => 
  array (
    'name' => 'price_per_hour',
    'type' => 'number',
    'step' => '0.01',
  ),
  8 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'available' => 'Available',
      'booked' => 'Booked',
      'occupied' => 'Occupied',
      'cleaning' => 'Cleaning',
      'maintenance' => 'Maintenance',
      'blocked' => 'Blocked',
    ),
    'default' => 'available',
  ),
  9 => 
  array (
    'name' => 'description',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'rooms',
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