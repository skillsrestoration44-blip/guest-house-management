<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\GuestDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class GuestDocumentController extends BaseCrudController
{
    protected string $modelClass = GuestDocument::class;
    protected string $route = 'admin.guest_documents';
    protected string $viewPath = 'admin.guest_documents';
    protected string $titleKey = 'guest_documents';
    protected bool $hasBranchScope = false;
    protected array $eagerLoad = ['guest'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'guest_id' => 'required|exists:guests,id',
            'document_type' => 'required|in:id_card,passport,visa,photo,other',
            'document_number' => 'nullable|string|max:100',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'file_path' => ($model ? 'sometimes' : 'required') . '|nullable|file|max:8192',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'guest.full_name', 'titleKey' => 'guest'],
            ['data' => 'document_type'],
            ['data' => 'document_number'],
            ['data' => 'issue_date'],
            ['data' => 'expiry_date'],
            ['data' => 'action', 'orderable' => false, 'searchable' => false],
        ];
    }

    protected function formViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'guest_id',
    'i18n' => 'guest',
    'type' => 'select',
    'options' => \App\Models\Guest::pluck('full_name', 'id')->toArray(),
    'required' => true,
  ),
  1 => 
  array (
    'name' => 'document_type',
    'type' => 'select',
    'options' => 
    array (
      'id_card' => 'Id Card',
      'passport' => 'Passport',
      'visa' => 'Visa',
      'photo' => 'Photo',
      'other' => 'Other',
    ),
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'document_number',
  ),
  3 => 
  array (
    'name' => 'issue_date',
    'type' => 'date',
  ),
  4 => 
  array (
    'name' => 'expiry_date',
    'type' => 'date',
  ),
  5 => 
  array (
    'name' => 'file_path',
    'i18n' => 'file',
    'type' => 'file',
    'required' => true,
  ),
),
            'titleKey' => 'guest_documents',
        ];
    }

    protected function showViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'guest_id',
    'i18n' => 'guest',
    'type' => 'select',
    'options' => \App\Models\Guest::pluck('full_name', 'id')->toArray(),
    'required' => true,
  ),
  1 => 
  array (
    'name' => 'document_type',
    'type' => 'select',
    'options' => 
    array (
      'id_card' => 'Id Card',
      'passport' => 'Passport',
      'visa' => 'Visa',
      'photo' => 'Photo',
      'other' => 'Other',
    ),
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'document_number',
  ),
  3 => 
  array (
    'name' => 'issue_date',
    'type' => 'date',
  ),
  4 => 
  array (
    'name' => 'expiry_date',
    'type' => 'date',
  ),
  5 => 
  array (
    'name' => 'file_path',
    'i18n' => 'file',
    'type' => 'file',
    'required' => true,
  ),
),
            'titleKey' => 'guest_documents',
        ];
    }

    protected function mutateData(Request $request, array $data, ?Model $model): array
    {
        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('guest_documents', 'public');
        } else {
            unset($data['file_path']);
        }
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