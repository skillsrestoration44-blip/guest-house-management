<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\NotificationTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class NotificationTemplateController extends BaseCrudController
{
    protected string $modelClass = NotificationTemplate::class;
    protected string $route = 'admin.notification_templates';
    protected string $viewPath = 'admin.notification_templates';
    protected string $titleKey = 'notification_templates';
    protected bool $hasBranchScope = false;
    protected array $eagerLoad = [];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'code' => 'required|string|max:100|unique:notification_templates,code,' . ($model?->id ?? 'NULL'),
            'title' => 'required|string|max:150',
            'message' => 'required|string',
            'channel' => 'required|in:system,sms,email,telegram,whatsapp',
            'status' => 'required|in:active,inactive',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'code'],
            ['data' => 'title'],
            ['data' => 'channel'],
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
    'name' => 'code',
    'required' => true,
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
  4 => 
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
            'titleKey' => 'notification_templates',
        ];
    }

    protected function showViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'code',
    'required' => true,
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
  4 => 
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
            'titleKey' => 'notification_templates',
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