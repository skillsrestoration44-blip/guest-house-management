@extends('admin.layouts.admin_layout')

@section('title', __('messages.document_versions'))
@section('page_title', __('messages.document_versions'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route($route . '.index') }}" data-i18n="document_versions">{{ __('messages.document_versions') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page" data-i18n="show">{{ __('messages.show') }}</li>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <span data-i18n="document_versions">{{ __('messages.document_versions') }}</span>
            <span class="text-muted">#{{ $model->id }}</span>
        </h5>
        <a href="{{ route($route . '.index') }}" class="btn btn-sm btn-light">
            <i class="bi bi-arrow-left"></i> <span data-i18n="back">{{ __('messages.back') }}</span>
        </a>
    </div>
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3" data-i18n="versionable_type">{{ __('messages.versionable_type') }}</dt>
            <dd class="col-sm-9">{{ class_basename($model->versionable_type) ?: $model->versionable_type }}</dd>

            <dt class="col-sm-3" data-i18n="versionable_id">{{ __('messages.versionable_id') }}</dt>
            <dd class="col-sm-9">{{ $model->versionable_id }}</dd>

            <dt class="col-sm-3" data-i18n="version_number">{{ __('messages.version_number') }}</dt>
            <dd class="col-sm-9"><span class="badge bg-primary">v{{ $model->version_number }}</span></dd>

            <dt class="col-sm-3" data-i18n="change_note">{{ __('messages.change_note') }}</dt>
            <dd class="col-sm-9">{{ $model->change_note ?: '—' }}</dd>

            <dt class="col-sm-3" data-i18n="users">{{ __('messages.users') }}</dt>
            <dd class="col-sm-9">{{ optional($model->creator)->name ?? '—' }}</dd>

            <dt class="col-sm-3" data-i18n="created_at">{{ __('messages.created_at') ?? 'Created At' }}</dt>
            <dd class="col-sm-9">{{ optional($model->created_at)->format('Y-m-d H:i') }}</dd>

            <dt class="col-sm-3" data-i18n="snapshot">{{ __('messages.snapshot') }}</dt>
            <dd class="col-sm-9">
                <pre class="bg-light p-3 rounded mb-0" style="max-height: 480px; overflow: auto;">{{ json_encode($model->snapshot, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
            </dd>
        </dl>
    </div>
</div>
@endsection
