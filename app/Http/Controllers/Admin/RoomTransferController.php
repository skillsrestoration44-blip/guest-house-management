<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\RoomTransfer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RoomTransferController extends BaseCrudController
{
    protected string $modelClass = RoomTransfer::class;
    protected string $route = 'admin.room_transfers';
    protected string $viewPath = 'admin.room_transfers';
    protected string $titleKey = 'room_transfers';
    protected bool $hasBranchScope = false;
    protected array $eagerLoad = ['stay', 'fromRoom', 'toRoom'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'stay_id' => 'required|exists:stays,id',
            'from_room_id' => 'required|exists:rooms,id',
            'to_room_id' => 'required|exists:rooms,id|different:from_room_id',
            'transfer_at' => 'required|date',
            'price_difference' => 'nullable|numeric',
            'reason' => 'nullable|string',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'stay.stay_no', 'titleKey' => 'stay_no'],
            ['data' => 'from_room.room_number', 'titleKey' => 'room'],
            ['data' => 'to_room.room_number', 'titleKey' => 'room'],
            ['data' => 'transfer_at'],
            ['data' => 'price_difference'],
            ['data' => 'action', 'orderable' => false, 'searchable' => false],
        ];
    }

    protected function formViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'stay_id',
    'i18n' => 'stays',
    'type' => 'select',
    'options' => \App\Models\Stay::pluck('stay_no', 'id')->toArray(),
    'required' => true,
  ),
  1 => 
  array (
    'name' => 'from_room_id',
    'i18n' => 'room',
    'type' => 'select',
    'options' => \App\Models\Room::pluck('room_number', 'id')->toArray(),
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'to_room_id',
    'i18n' => 'room',
    'type' => 'select',
    'options' => \App\Models\Room::pluck('room_number', 'id')->toArray(),
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'transfer_at',
    'type' => 'datetime',
    'required' => true,
  ),
  4 => 
  array (
    'name' => 'price_difference',
    'type' => 'number',
    'step' => '0.01',
  ),
  5 => 
  array (
    'name' => 'reason',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'room_transfers',
        ];
    }

    protected function showViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'stay_id',
    'i18n' => 'stays',
    'type' => 'select',
    'options' => \App\Models\Stay::pluck('stay_no', 'id')->toArray(),
    'required' => true,
  ),
  1 => 
  array (
    'name' => 'from_room_id',
    'i18n' => 'room',
    'type' => 'select',
    'options' => \App\Models\Room::pluck('room_number', 'id')->toArray(),
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'to_room_id',
    'i18n' => 'room',
    'type' => 'select',
    'options' => \App\Models\Room::pluck('room_number', 'id')->toArray(),
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'transfer_at',
    'type' => 'datetime',
    'required' => true,
  ),
  4 => 
  array (
    'name' => 'price_difference',
    'type' => 'number',
    'step' => '0.01',
  ),
  5 => 
  array (
    'name' => 'reason',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'room_transfers',
        ];
    }

    protected function mutateData(Request $request, array $data, ?Model $model): array
    {
        return $data;
    }

    protected function afterStore(\Illuminate\Http\Request $request, \Illuminate\Database\Eloquent\Model $model): void
    {
        if (in_array('transferred_by', $model->getFillable(), true) || \Illuminate\Support\Facades\Schema::hasColumn($model->getTable(), 'transferred_by')) {
            $model->forceFill(['transferred_by' => auth()->id()])->save();
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