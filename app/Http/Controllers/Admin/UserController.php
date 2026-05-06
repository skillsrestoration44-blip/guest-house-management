<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserController extends BaseCrudController
{
    protected string $modelClass = User::class;
    protected string $route = 'admin.users';
    protected string $viewPath = 'admin.users';
    protected string $titleKey = 'users';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = [];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'staff_id' => 'nullable|exists:staff,id',
            'name' => 'required|string|max:150',
            'username' => 'required|string|max:100|unique:users,username,' . ($model?->id ?? 'NULL'),
            'email' => 'required|email|max:150|unique:users,email,' . ($model?->id ?? 'NULL'),
            'phone' => 'nullable|string|max:50',
            'password' => ($model ? 'sometimes' : 'required') . '|nullable|string|min:6',
            'status' => 'required|in:active,inactive,blocked',
            'avatar' => 'nullable|file|image|max:4096',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id', 'titleKey' => 'username'],
            ['data' => 'username', 'titleKey' => 'username'],
            ['data' => 'name', 'titleKey' => 'name'],
            ['data' => 'email', 'titleKey' => 'email'],
            ['data' => 'phone', 'titleKey' => 'phone'],
            ['data' => 'status', 'titleKey' => 'status'],
            ['data' => 'action', 'titleKey' => 'action', 'orderable' => false, 'searchable' => false],
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
    'name' => 'staff_id',
    'i18n' => 'staff',
    'type' => 'select',
    'options' => \App\Models\Staff::pluck('full_name', 'id')->toArray(),
  ),
  2 => 
  array (
    'name' => 'name',
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'username',
    'required' => true,
  ),
  4 => 
  array (
    'name' => 'email',
    'type' => 'email',
    'required' => true,
  ),
  5 => 
  array (
    'name' => 'phone',
  ),
  6 => 
  array (
    'name' => 'password',
    'type' => 'password',
    'help' => 'Leave blank to keep current',
  ),
  7 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'active' => 'Active',
      'inactive' => 'Inactive',
      'blocked' => 'Blocked',
    ),
    'default' => 'active',
  ),
  8 => 
  array (
    'name' => 'avatar',
    'type' => 'file',
  ),
),
            'titleKey' => 'users',
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
    'name' => 'staff_id',
    'i18n' => 'staff',
    'type' => 'select',
    'options' => \App\Models\Staff::pluck('full_name', 'id')->toArray(),
  ),
  2 => 
  array (
    'name' => 'name',
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'username',
    'required' => true,
  ),
  4 => 
  array (
    'name' => 'email',
    'type' => 'email',
    'required' => true,
  ),
  5 => 
  array (
    'name' => 'phone',
  ),
  6 => 
  array (
    'name' => 'password',
    'type' => 'password',
    'help' => 'Leave blank to keep current',
  ),
  7 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'active' => 'Active',
      'inactive' => 'Inactive',
      'blocked' => 'Blocked',
    ),
    'default' => 'active',
  ),
  8 => 
  array (
    'name' => 'avatar',
    'type' => 'file',
  ),
),
            'titleKey' => 'users',
        ];
    }

    protected function mutateData(Request $request, array $data, ?Model $model): array
    {
        if (empty($data['password'])) {
            unset($data['password']);
        }
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