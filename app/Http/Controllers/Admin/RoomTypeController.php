<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RoomTypeController extends BaseCrudController
{
    protected string $modelClass = RoomType::class;
    protected string $route = 'admin.room_types';
    protected string $viewPath = 'admin.room_types';
    protected string $titleKey = 'room_types';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = [];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'name' => 'required|string|max:100',
            'default_price_per_night' => 'required|numeric|min:0',
            'default_price_per_hour' => 'nullable|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
            'bed_count' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'name'],
            ['data' => 'default_price_per_night', 'titleKey' => 'price_per_night'],
            ['data' => 'default_price_per_hour', 'titleKey' => 'price_per_hour'],
            ['data' => 'max_guests'],
            ['data' => 'bed_count'],
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
    'name' => 'name',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'default_price_per_night',
    'i18n' => 'price_per_night',
    'type' => 'number',
    'step' => '0.01',
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'default_price_per_hour',
    'i18n' => 'price_per_hour',
    'type' => 'number',
    'step' => '0.01',
  ),
  4 => 
  array (
    'name' => 'max_guests',
    'type' => 'number',
    'default' => 1,
  ),
  5 => 
  array (
    'name' => 'bed_count',
    'type' => 'number',
    'default' => 1,
  ),
  6 => 
  array (
    'name' => 'description',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
  7 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'active' => 'Active',
      'inactive' => 'Inactive',
    ),
    'default' => 'active',
  ),
),
            'titleKey' => 'room_types',
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
    'name' => 'name',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'default_price_per_night',
    'i18n' => 'price_per_night',
    'type' => 'number',
    'step' => '0.01',
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'default_price_per_hour',
    'i18n' => 'price_per_hour',
    'type' => 'number',
    'step' => '0.01',
  ),
  4 => 
  array (
    'name' => 'max_guests',
    'type' => 'number',
    'default' => 1,
  ),
  5 => 
  array (
    'name' => 'bed_count',
    'type' => 'number',
    'default' => 1,
  ),
  6 => 
  array (
    'name' => 'description',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
  7 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'active' => 'Active',
      'inactive' => 'Inactive',
    ),
    'default' => 'active',
  ),
),
            'titleKey' => 'room_types',
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