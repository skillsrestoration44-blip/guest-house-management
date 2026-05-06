<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AuditLogController extends BaseCrudController
{
    protected string $modelClass = AuditLog::class;
    protected string $route = 'admin.audit_logs';
    protected string $viewPath = 'admin.audit_logs';
    protected string $titleKey = 'audit_logs';
    protected bool $hasBranchScope = true;
    protected array $eagerLoad = ['user'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'user.name', 'titleKey' => 'users'],
            ['data' => 'action'],
            ['data' => 'module'],
            ['data' => 'auditable_type'],
            ['data' => 'auditable_id'],
            ['data' => 'created_at'],
        ];
    }

    protected function formViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return ['fields' => [], 'titleKey' => 'audit_logs'];
    }

    protected function showViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return ['fields' => [], 'titleKey' => 'audit_logs'];
    }

    protected function mutateData(Request $request, array $data, ?Model $model): array
    {
        return $data;
    }


    public function create(): \Illuminate\View\View
    {
        abort(403);
    }

    public function store(\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse
    {
        abort(403);
    }

    public function edit(int $id): \Illuminate\View\View
    {
        abort(403);
    }

    public function update(\Illuminate\Http\Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        abort(403);
    }

    public function datatable(\Illuminate\Http\Request $request): \Illuminate\Http\JsonResponse
    {
        $query = $this->newQuery();
        return \Yajra\DataTables\Facades\DataTables::eloquent($query)->make(true);
    }
    protected function indexViewData(\Illuminate\Http\Request $request): array
    {
        return [
            'route' => $this->route,
            'columns' => $this->tableColumns(),
            'titleKey' => $this->titleKey,
            'readOnly' => true,
        ];
    }
}