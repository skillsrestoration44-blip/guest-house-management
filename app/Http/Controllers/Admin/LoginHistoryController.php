<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseCrudController;
use App\Models\LoginHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class LoginHistoryController extends BaseCrudController
{
    protected string $modelClass = LoginHistory::class;
    protected string $route = 'admin.login_histories';
    protected string $viewPath = 'admin.login_histories';
    protected string $titleKey = 'login_histories';
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
            ['data' => 'ip_address'],
            ['data' => 'login_at'],
            ['data' => 'logout_at'],
            ['data' => 'status'],
        ];
    }

    protected function formViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return ['fields' => [], 'titleKey' => 'login_histories'];
    }

    protected function showViewData(\Illuminate\Database\Eloquent\Model $model): array
    {
        return ['fields' => [], 'titleKey' => 'login_histories'];
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