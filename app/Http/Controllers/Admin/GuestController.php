<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\Guest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class GuestController extends BaseCrudController
{
    protected string $modelClass = Guest::class;
    protected string $route = 'admin.guests';
    protected string $viewPath = 'admin.guests';
    protected string $titleKey = 'guests';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = [];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'guest_code' => 'required|string|max:50|unique:guests,guest_code,' . ($model?->id ?? 'NULL'),
            'full_name' => 'required|string|max:150',
            'gender' => 'nullable|in:male,female,other',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'nationality' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'photo' => 'nullable|file|image|max:4096',
            'is_blacklisted' => 'nullable|boolean',
            'blacklist_reason' => 'nullable|string',
            'note' => 'nullable|string',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'guest_code'],
            ['data' => 'full_name'],
            ['data' => 'phone'],
            ['data' => 'email'],
            ['data' => 'nationality'],
            ['data' => 'is_blacklisted'],
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
    'name' => 'guest_code',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'full_name',
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'gender',
    'type' => 'select',
    'options' => 
    array (
      'male' => 'Male',
      'female' => 'Female',
      'other' => 'Other',
    ),
  ),
  4 => 
  array (
    'name' => 'phone',
  ),
  5 => 
  array (
    'name' => 'email',
    'type' => 'email',
  ),
  6 => 
  array (
    'name' => 'nationality',
  ),
  7 => 
  array (
    'name' => 'date_of_birth',
    'type' => 'date',
  ),
  8 => 
  array (
    'name' => 'address',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
  9 => 
  array (
    'name' => 'photo',
    'type' => 'file',
  ),
  10 => 
  array (
    'name' => 'is_blacklisted',
    'type' => 'checkbox',
  ),
  11 => 
  array (
    'name' => 'blacklist_reason',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
  12 => 
  array (
    'name' => 'note',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'guests',
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
    'name' => 'guest_code',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'full_name',
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'gender',
    'type' => 'select',
    'options' => 
    array (
      'male' => 'Male',
      'female' => 'Female',
      'other' => 'Other',
    ),
  ),
  4 => 
  array (
    'name' => 'phone',
  ),
  5 => 
  array (
    'name' => 'email',
    'type' => 'email',
  ),
  6 => 
  array (
    'name' => 'nationality',
  ),
  7 => 
  array (
    'name' => 'date_of_birth',
    'type' => 'date',
  ),
  8 => 
  array (
    'name' => 'address',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
  9 => 
  array (
    'name' => 'photo',
    'type' => 'file',
  ),
  10 => 
  array (
    'name' => 'is_blacklisted',
    'type' => 'checkbox',
  ),
  11 => 
  array (
    'name' => 'blacklist_reason',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
  12 => 
  array (
    'name' => 'note',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
),
            'titleKey' => 'guests',
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