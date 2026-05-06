<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\GuestHouseSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class GuestHouseSettingController extends BaseCrudController
{
    protected string $modelClass = GuestHouseSetting::class;
    protected string $route = 'admin.guest_house_settings';
    protected string $viewPath = 'admin.guest_house_settings';
    protected string $titleKey = 'guest_house_settings';
    protected bool $hasBranchScope = false;
    protected array $eagerLoad = [];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'website' => 'nullable|string|max:150',
            'tax_number' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'currency' => 'required|string|max:10',
            'timezone' => 'required|string|max:100',
            'logo' => 'nullable|file|image|max:4096',
            'stamp_image' => 'nullable|file|image|max:4096',
            'signature_image' => 'nullable|file|image|max:4096',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'name'],
            ['data' => 'phone'],
            ['data' => 'email'],
            ['data' => 'currency'],
            ['data' => 'action', 'orderable' => false, 'searchable' => false],
        ];
    }

    protected function formViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'name',
    'required' => true,
  ),
  1 => 
  array (
    'name' => 'phone',
  ),
  2 => 
  array (
    'name' => 'email',
    'type' => 'email',
  ),
  3 => 
  array (
    'name' => 'website',
  ),
  4 => 
  array (
    'name' => 'tax_number',
  ),
  5 => 
  array (
    'name' => 'address',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
  6 => 
  array (
    'name' => 'currency',
    'default' => 'USD',
  ),
  7 => 
  array (
    'name' => 'timezone',
    'default' => 'Asia/Phnom_Penh',
  ),
  8 => 
  array (
    'name' => 'logo',
    'type' => 'file',
  ),
  9 => 
  array (
    'name' => 'stamp_image',
    'type' => 'file',
  ),
  10 => 
  array (
    'name' => 'signature_image',
    'type' => 'file',
  ),
),
            'titleKey' => 'guest_house_settings',
        ];
    }

    protected function showViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'name',
    'required' => true,
  ),
  1 => 
  array (
    'name' => 'phone',
  ),
  2 => 
  array (
    'name' => 'email',
    'type' => 'email',
  ),
  3 => 
  array (
    'name' => 'website',
  ),
  4 => 
  array (
    'name' => 'tax_number',
  ),
  5 => 
  array (
    'name' => 'address',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
  6 => 
  array (
    'name' => 'currency',
    'default' => 'USD',
  ),
  7 => 
  array (
    'name' => 'timezone',
    'default' => 'Asia/Phnom_Penh',
  ),
  8 => 
  array (
    'name' => 'logo',
    'type' => 'file',
  ),
  9 => 
  array (
    'name' => 'stamp_image',
    'type' => 'file',
  ),
  10 => 
  array (
    'name' => 'signature_image',
    'type' => 'file',
  ),
),
            'titleKey' => 'guest_house_settings',
        ];
    }

    protected function mutateData(Request $request, array $data, ?Model $model): array
    {
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('settings', 'public');
        } else {
            unset($data['logo']);
        }
        if ($request->hasFile('stamp_image')) {
            $data['stamp_image'] = $request->file('stamp_image')->store('settings', 'public');
        } else {
            unset($data['stamp_image']);
        }
        if ($request->hasFile('signature_image')) {
            $data['signature_image'] = $request->file('signature_image')->store('settings', 'public');
        } else {
            unset($data['signature_image']);
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