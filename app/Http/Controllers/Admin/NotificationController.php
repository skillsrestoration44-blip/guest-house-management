<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class NotificationController extends BaseCrudController
{
    protected string $modelClass = Notification::class;
    protected string $route = 'admin.notifications';
    protected string $viewPath = 'admin.notifications';
    protected string $titleKey = 'notifications';
    protected bool $hasBranchScope = false;
    protected array $eagerLoad = ['user'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'user_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:150',
            'message' => 'required|string',
            'type' => 'required|in:system,booking,payment,debt,housekeeping,maintenance,stock',
            'channel' => 'required|in:system,sms,email,telegram,whatsapp',
            'is_read' => 'nullable|boolean',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'title'],
            ['data' => 'type'],
            ['data' => 'channel'],
            ['data' => 'user.name', 'titleKey' => 'users'],
            ['data' => 'is_read'],
            ['data' => 'created_at'],
            ['data' => 'action', 'orderable' => false, 'searchable' => false],
        ];
    }

    protected function formViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'user_id',
    'i18n' => 'users',
    'type' => 'select',
    'options' => \App\Models\User::pluck('name', 'id')->toArray(),
  ),
  1 => 
  array (
    'name' => 'title',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'message',
    'type' => 'textarea',
    'col' => 'col-12',
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'type',
    'type' => 'select',
    'options' => 
    array (
      'system' => 'System',
      'booking' => 'Booking',
      'payment' => 'Payment',
      'debt' => 'Debt',
      'housekeeping' => 'Housekeeping',
      'maintenance' => 'Maintenance',
      'stock' => 'Stock',
    ),
    'default' => 'system',
  ),
  4 => 
  array (
    'name' => 'channel',
    'type' => 'select',
    'options' => 
    array (
      'system' => 'System',
      'sms' => 'Sms',
      'email' => 'Email',
      'telegram' => 'Telegram',
      'whatsapp' => 'Whatsapp',
    ),
    'default' => 'system',
  ),
  5 => 
  array (
    'name' => 'is_read',
    'type' => 'checkbox',
  ),
),
            'titleKey' => 'notifications',
        ];
    }

    protected function showViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'user_id',
    'i18n' => 'users',
    'type' => 'select',
    'options' => \App\Models\User::pluck('name', 'id')->toArray(),
  ),
  1 => 
  array (
    'name' => 'title',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'message',
    'type' => 'textarea',
    'col' => 'col-12',
    'required' => true,
  ),
  3 => 
  array (
    'name' => 'type',
    'type' => 'select',
    'options' => 
    array (
      'system' => 'System',
      'booking' => 'Booking',
      'payment' => 'Payment',
      'debt' => 'Debt',
      'housekeeping' => 'Housekeeping',
      'maintenance' => 'Maintenance',
      'stock' => 'Stock',
    ),
    'default' => 'system',
  ),
  4 => 
  array (
    'name' => 'channel',
    'type' => 'select',
    'options' => 
    array (
      'system' => 'System',
      'sms' => 'Sms',
      'email' => 'Email',
      'telegram' => 'Telegram',
      'whatsapp' => 'Whatsapp',
    ),
    'default' => 'system',
  ),
  5 => 
  array (
    'name' => 'is_read',
    'type' => 'checkbox',
  ),
),
            'titleKey' => 'notifications',
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