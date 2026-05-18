<?php

namespace App\Http\Controllers\Admin;

use App\Models\DocumentVersion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

/**
 * ISO 9001 §4.2.3 — Control of Documents.
 *
 * Document versions are immutable historical snapshots produced automatically
 * whenever a controlled document (e.g. WebsitePage policy text) is updated.
 * The admin UI is therefore read-only: browse history, view a snapshot,
 * but creation / editing / deletion are blocked at the controller level.
 */
class DocumentVersionController extends BaseCrudController
{
    protected string $modelClass = DocumentVersion::class;
    protected string $route = 'admin.document-versions';
    protected string $viewPath = 'admin.document_versions';
    protected string $titleKey = 'document_versions';
    protected bool $hasBranchScope = false;
    protected array $eagerLoad = ['creator'];

    protected function rules(Request $request, ?Model $model = null): array
    {
        return [];
    }

    protected function tableColumns(): array
    {
        return [
            ['data' => 'id'],
            ['data' => 'versionable_type'],
            ['data' => 'versionable_id'],
            ['data' => 'version_number'],
            ['data' => 'change_note'],
            ['data' => 'creator.name', 'name' => 'creator.name', 'titleKey' => 'users'],
            ['data' => 'created_at'],
            ['data' => 'action', 'orderable' => false, 'searchable' => false],
        ];
    }

    protected function formViewData(Model $model): array
    {
        return ['fields' => [], 'titleKey' => $this->titleKey];
    }

    protected function showViewData(Model $model): array
    {
        return ['fields' => [], 'titleKey' => $this->titleKey];
    }

    protected function mutateData(Request $request, array $data, ?Model $model): array
    {
        return $data;
    }

    /* Document versions are immutable historical snapshots — block all writes. */
    public function create(): \Illuminate\View\View
    {
        abort(403);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        abort(403);
    }

    public function edit(int $id): \Illuminate\View\View
    {
        abort(403);
    }

    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        abort(403);
    }

    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        abort(403);
    }

    public function datatable(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = $this->newQuery();

        return DataTables::eloquent($query)
            ->editColumn('versionable_type', function ($row) {
                $short = class_basename($row->versionable_type);
                return $short !== '' ? $short : (string) $row->versionable_type;
            })
            ->addColumn('action', function ($row) {
                $showUrl = route($this->route . '.show', $row->id);
                return '<a href="' . $showUrl . '" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    protected function indexViewData(Request $request): array
    {
        return [
            'route' => $this->route,
            'columns' => $this->tableColumns(),
            'titleKey' => $this->titleKey,
            'readOnly' => true,
        ];
    }
}
