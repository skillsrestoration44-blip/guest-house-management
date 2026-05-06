<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\Receipt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ReceiptController extends BaseCrudController
{
    protected string $modelClass = Receipt::class;
    protected string $route = 'admin.receipts';
    protected string $viewPath = 'admin.receipts';
    protected string $titleKey = 'receipts';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = ['payment', 'invoice'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'receipt_no' => 'required|string|max:50|unique:receipts,receipt_no,' . ($model?->id ?? 'NULL'),
            'payment_id' => 'required|exists:payments,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'issued_at' => 'required|date',
            'pdf_path' => 'nullable|file',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'receipt_no'],
            ['data' => 'payment.payment_no', 'titleKey' => 'payment_no'],
            ['data' => 'invoice.invoice_no', 'titleKey' => 'invoice_no'],
            ['data' => 'issued_at'],
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
    'name' => 'receipt_no',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'payment_id',
    'i18n' => 'payment_no',
    'type' => 'select',
    'options' => \App\Models\Payment::pluck('payment_no', 'id')->toArray(),
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'invoice_id',
    'i18n' => 'invoice_no',
    'type' => 'select',
    'options' => \App\Models\Invoice::pluck('invoice_no', 'id')->toArray(),
  ),
  4 => 
  array (
    'name' => 'issued_at',
    'type' => 'datetime',
    'required' => true,
  ),
  5 => 
  array (
    'name' => 'pdf_path',
    'type' => 'file',
    'accept' => 'application/pdf',
  ),
),
            'titleKey' => 'receipts',
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
    'name' => 'receipt_no',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'payment_id',
    'i18n' => 'payment_no',
    'type' => 'select',
    'options' => \App\Models\Payment::pluck('payment_no', 'id')->toArray(),
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'invoice_id',
    'i18n' => 'invoice_no',
    'type' => 'select',
    'options' => \App\Models\Invoice::pluck('invoice_no', 'id')->toArray(),
  ),
  4 => 
  array (
    'name' => 'issued_at',
    'type' => 'datetime',
    'required' => true,
  ),
  5 => 
  array (
    'name' => 'pdf_path',
    'type' => 'file',
    'accept' => 'application/pdf',
  ),
),
            'titleKey' => 'receipts',
        ];
    }

    protected function mutateData(Request $request, array $data, ?Model $model): array
    {
        if ($request->hasFile('pdf_path')) {
            $data['pdf_path'] = $request->file('pdf_path')->store('receipts', 'public');
        } else {
            unset($data['pdf_path']);
        }
        return $data;
    }

    protected function afterStore(\Illuminate\Http\Request $request, \Illuminate\Database\Eloquent\Model $model): void
    {
        if (in_array('issued_by', $model->getFillable(), true) || \Illuminate\Support\Facades\Schema::hasColumn($model->getTable(), 'issued_by')) {
            $model->forceFill(['issued_by' => auth()->id()])->save();
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