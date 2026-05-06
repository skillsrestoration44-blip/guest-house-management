<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class StockMovementController extends BaseCrudController
{
    protected string $modelClass = StockMovement::class;
    protected string $route = 'admin.stock_movements';
    protected string $viewPath = 'admin.stock_movements';
    protected string $titleKey = 'stock_movements';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = ['item'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'stock_item_id' => 'required|exists:stock_items,id',
            'movement_type' => 'required|in:in,out,adjustment,damaged,expired',
            'quantity' => 'required|numeric',
            'unit_cost' => 'nullable|numeric',
            'total_cost' => 'nullable|numeric',
            'movement_at' => 'required|date',
            'note' => 'nullable|string',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'item.name', 'titleKey' => 'stock_items'],
            ['data' => 'movement_type'],
            ['data' => 'quantity'],
            ['data' => 'movement_at'],
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
    'name' => 'stock_item_id',
    'i18n' => 'stock_items',
    'type' => 'select',
    'options' => \App\Models\StockItem::pluck('name', 'id')->toArray(),
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'movement_type',
    'type' => 'select',
    'options' => 
    array (
      'in' => 'In',
      'out' => 'Out',
      'adjustment' => 'Adjustment',
      'damaged' => 'Damaged',
      'expired' => 'Expired',
    ),
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'quantity',
    'type' => 'number',
    'step' => '0.01',
    'required' => true,
  ),
  4 => 
  array (
    'name' => 'unit_cost',
    'type' => 'number',
    'step' => '0.01',
  ),
  5 => 
  array (
    'name' => 'total_cost',
    'type' => 'number',
    'step' => '0.01',
  ),
  6 => 
  array (
    'name' => 'movement_at',
    'type' => 'datetime',
    'required' => true,
  ),
  7 => 
  array (
    'name' => 'note',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'stock_movements',
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
    'name' => 'stock_item_id',
    'i18n' => 'stock_items',
    'type' => 'select',
    'options' => \App\Models\StockItem::pluck('name', 'id')->toArray(),
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'movement_type',
    'type' => 'select',
    'options' => 
    array (
      'in' => 'In',
      'out' => 'Out',
      'adjustment' => 'Adjustment',
      'damaged' => 'Damaged',
      'expired' => 'Expired',
    ),
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'quantity',
    'type' => 'number',
    'step' => '0.01',
    'required' => true,
  ),
  4 => 
  array (
    'name' => 'unit_cost',
    'type' => 'number',
    'step' => '0.01',
  ),
  5 => 
  array (
    'name' => 'total_cost',
    'type' => 'number',
    'step' => '0.01',
  ),
  6 => 
  array (
    'name' => 'movement_at',
    'type' => 'datetime',
    'required' => true,
  ),
  7 => 
  array (
    'name' => 'note',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'stock_movements',
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