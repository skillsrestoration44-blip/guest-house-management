<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\WebsitePage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class WebsitePageController extends BaseCrudController
{
    protected string $modelClass = WebsitePage::class;
    protected string $route = 'admin.website_pages';
    protected string $viewPath = 'admin.website_pages';
    protected string $titleKey = 'website_pages';
    protected bool $hasBranchScope = false;
    protected array $eagerLoad = [];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [
            'slug' => 'required|string|max:150|unique:website_pages,slug,' . ($model?->id ?? 'NULL'),
            'title' => 'required|string|max:150',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:150',
            'meta_description' => 'nullable|string',
            'status' => 'required|in:draft,published',
        ];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'slug'],
            ['data' => 'title'],
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
    'name' => 'slug',
    'required' => true,
  ),
  1 => 
  array (
    'name' => 'title',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'content',
    'type' => 'textarea',
    'col' => 'col-12',
    'rows' => 8,
  ),
  3 => 
  array (
    'name' => 'meta_title',
  ),
  4 => 
  array (
    'name' => 'meta_description',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
  5 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'draft' => 'Draft',
      'published' => 'Published',
    ),
    'default' => 'draft',
  ),
),
            'titleKey' => 'website_pages',
        ];
    }

    protected function showViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return [
            'fields' => array (
  0 => 
  array (
    'name' => 'slug',
    'required' => true,
  ),
  1 => 
  array (
    'name' => 'title',
    'required' => true,
  ),
  2 => 
  array (
    'name' => 'content',
    'type' => 'textarea',
    'col' => 'col-12',
    'rows' => 8,
  ),
  3 => 
  array (
    'name' => 'meta_title',
  ),
  4 => 
  array (
    'name' => 'meta_description',
    'type' => 'textarea',
    'col' => 'col-12',
  ),
  5 => 
  array (
    'name' => 'status',
    'type' => 'select',
    'options' => 
    array (
      'draft' => 'Draft',
      'published' => 'Published',
    ),
    'default' => 'draft',
  ),
),
            'titleKey' => 'website_pages',
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