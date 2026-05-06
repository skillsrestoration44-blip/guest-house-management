<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\StockItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class StockItemController extends BaseCrudController
{
    protected string $modelClass = StockItem::class;
    protected string $route = 'admin.stock_items';
    protected string $viewPath = 'admin.stock_items';
    protected string $titleKey = 'stock_items';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = ['category', 'supplier'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'stock_category_id' => 'required|exists:stock_categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'name' => 'required|string|max:150',
            'sku' => 'nullable|string|max:100',
            'unit' => 'required|string|max:50',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'current_stock' => 'nullable|numeric|min:0',
            'minimum_stock' => 'nullable|numeric|min:0',
            'expiry_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'name'],
            ['data' => 'sku'],
            ['data' => 'category.name', 'titleKey' => 'stock_categories'],
            ['data' => 'unit'],
            ['data' => 'current_stock'],
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
    'name' => 'stock_category_id',
    'i18n' => 'stock_categories',
    'type' => 'select',
    'options' => \App\Models\StockCategory::pluck('name', 'id')->toArray(),
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'supplier_id',
    'i18n' => 'suppliers',
    'type' => 'select',
    'options' => \App\Models\Supplier::pluck('name', 'id')->toArray(),
  ),
  3 => 
  array (
    'name' => 'name',
    'required' => true,
  ),
  4 => 
  array (
    'name' => 'sku',
  ),
  5 => 
  array (
    'name' => 'unit',
    'default' => 'pcs',
  ),
  6 => 
  array (
    'name' => 'purchase_price',
    'type' => 'number',
    'step' => '0.01',
  ),
  7 => 
  array (
    'name' => 'selling_price',
    'type' => 'number',
    'step' => '0.01',
  ),
  8 => 
  array (
    'name' => 'current_stock',
    'type' => 'number',
    'step' => '0.01',
  ),
  9 => 
  array (
    'name' => 'minimum_stock',
    'type' => 'number',
    'step' => '0.01',
  ),
  10 => 
  array (
    'name' => 'expiry_date',
    'type' => 'date',
  ),
  11 => 
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
            'titleKey' => 'stock_items',
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
    'name' => 'stock_category_id',
    'i18n' => 'stock_categories',
    'type' => 'select',
    'options' => \App\Models\StockCategory::pluck('name', 'id')->toArray(),
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'supplier_id',
    'i18n' => 'suppliers',
    'type' => 'select',
    'options' => \App\Models\Supplier::pluck('name', 'id')->toArray(),
  ),
  3 => 
  array (
    'name' => 'name',
    'required' => true,
  ),
  4 => 
  array (
    'name' => 'sku',
  ),
  5 => 
  array (
    'name' => 'unit',
    'default' => 'pcs',
  ),
  6 => 
  array (
    'name' => 'purchase_price',
    'type' => 'number',
    'step' => '0.01',
  ),
  7 => 
  array (
    'name' => 'selling_price',
    'type' => 'number',
    'step' => '0.01',
  ),
  8 => 
  array (
    'name' => 'current_stock',
    'type' => 'number',
    'step' => '0.01',
  ),
  9 => 
  array (
    'name' => 'minimum_stock',
    'type' => 'number',
    'step' => '0.01',
  ),
  10 => 
  array (
    'name' => 'expiry_date',
    'type' => 'date',
  ),
  11 => 
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
            'titleKey' => 'stock_items',
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