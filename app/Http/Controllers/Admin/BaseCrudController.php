<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ResolvesBranch;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

/**
 * Generic admin CRUD controller. Subclasses must declare:
 *   - $modelClass    : Eloquent model class name.
 *   - $route         : route name prefix (e.g. "admin.branches").
 *   - $viewPath      : blade view directory (e.g. "admin.branches").
 *   - $titleKey      : translation key for the page title.
 *   - $hasBranchScope: true if model has a branch_id column.
 *   - rules()        : validation rules.
 *   - tableColumns() : DataTables column descriptors.
 */
abstract class BaseCrudController extends Controller
{
    use ResolvesBranch;

    protected string $modelClass;
    protected string $route;
    protected string $viewPath;
    protected string $titleKey = 'list';
    protected bool $hasBranchScope = false;
    protected array $eagerLoad = [];
    protected array $searchable = [];

    abstract protected function rules(Request $request, ?Model $model = null): array;

    abstract protected function tableColumns(): array;

    protected function transformRow($row): array
    {
        return $row->toArray();
    }

    protected function newQuery(): Builder
    {
        $query = $this->modelClass::query();
        if ($this->eagerLoad) {
            $query->with($this->eagerLoad);
        }
        if ($this->hasBranchScope) {
            $query = $this->applyBranchScope($query);
        }

        return $query;
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->datatable($request);
        }

        return view($this->viewPath . '.index', $this->indexViewData($request));
    }

    protected function indexViewData(Request $request): array
    {
        return [
            'route' => $this->route,
            'columns' => $this->tableColumns(),
            'titleKey' => $this->titleKey,
        ];
    }

    public function datatable(Request $request): JsonResponse
    {
        $query = $this->newQuery();

        return DataTables::eloquent($query)
            ->addColumn('action', function ($row) {
                $editUrl = route($this->route . '.edit', $row->id);
                $destroyUrl = route($this->route . '.destroy', $row->id);
                $tableId = str_replace('.', '_', $this->route) . '_table';

                $editLabel = __('messages.edit');
                $deleteLabel = __('messages.delete');

                return '<div class="d-flex gap-1">'
                    . '<a href="' . $editUrl . '" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i> <span data-i18n="edit">' . $editLabel . '</span></a>'
                    . '<button type="button" class="btn btn-sm btn-outline-danger js-delete" data-url="' . $destroyUrl . '" data-table="' . $tableId . '"><i class="bi bi-trash"></i> <span data-i18n="delete">' . $deleteLabel . '</span></button>'
                    . '</div>';
            })
            ->rawColumns(array_merge($this->rawColumns(), ['action']))
            ->make(true);
    }

    protected function rawColumns(): array
    {
        return [];
    }

    public function create(): View
    {
        $model = new $this->modelClass();
        return view($this->viewPath . '.create', array_merge([
            'model' => $model,
            'route' => $this->route,
            'mode' => 'create',
        ], $this->formViewData($model)));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules($request, null));
        $data = $this->mutateData($request, $data, null);

        $model = new $this->modelClass();
        $model->fill($data);
        if ($this->hasBranchScope && empty($data['branch_id'])) {
            $model->branch_id = $this->currentBranchId();
        }
        $model->save();

        $this->afterStore($request, $model);

        sweetalert()->success(__('messages.created_successfully'));
        return redirect()->route($this->route . '.index');
    }

    public function show(int $id): View
    {
        $model = $this->newQuery()->findOrFail($id);

        return view($this->viewPath . '.show', array_merge([
            'model' => $model,
            'route' => $this->route,
        ], $this->showViewData($model)));
    }

    public function edit(int $id): View
    {
        $model = $this->newQuery()->findOrFail($id);

        return view($this->viewPath . '.edit', array_merge([
            'model' => $model,
            'route' => $this->route,
            'mode' => 'edit',
        ], $this->formViewData($model)));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $model = $this->modelClass::findOrFail($id);

        $data = $request->validate($this->rules($request, $model));
        $data = $this->mutateData($request, $data, $model);

        $model->fill($data)->save();

        $this->afterUpdate($request, $model);

        sweetalert()->success(__('messages.updated_successfully'));
        return redirect()->route($this->route . '.index');
    }

    public function destroy(int $id): JsonResponse
    {
        $model = $this->modelClass::findOrFail($id);
        $model->delete();

        return response()->json([
            'status' => 'success',
            'message' => __('messages.deleted_successfully'),
        ]);
    }

    protected function formViewData(Model $model): array
    {
        return [];
    }

    protected function showViewData(Model $model): array
    {
        return [];
    }

    protected function mutateData(Request $request, array $data, ?Model $model): array
    {
        return $data;
    }

    protected function afterStore(Request $request, Model $model): void {}
    protected function afterUpdate(Request $request, Model $model): void {}
}
