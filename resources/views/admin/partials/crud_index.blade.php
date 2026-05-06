@extends('admin.layouts.admin_layout')

@section('title', __('messages.' . $titleKey))
@section('page_title', __('messages.' . $titleKey))
@section('breadcrumb')
    <li class="breadcrumb-item"><span data-i18n="{{ $titleKey }}">{{ __('messages.' . $titleKey) }}</span></li>
    <li class="breadcrumb-item active" aria-current="page" data-i18n="list">{{ __('messages.list') }}</li>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0" data-i18n="{{ $titleKey }}">{{ __('messages.' . $titleKey) }}</h5>
        @unless($readOnly ?? false)
        <a href="{{ route($route . '.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> <span data-i18n="create">{{ __('messages.create') }}</span>
        </a>
        @endunless
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @php $tableId = str_replace('.', '_', $route) . '_table'; @endphp
            <table id="{{ $tableId }}" class="table table-bordered table-striped table-hover js-datatable w-100"
                   data-url="{{ route($route . '.index') }}"
                   data-columns='@json($columns)'
                   data-order='[[0, "desc"]]'>
                <thead>
                    <tr>
                        @foreach($columns as $col)
                            <th data-i18n="{{ $col['titleKey'] ?? '' }}">
                                {{ $col['title'] ?? __('messages.' . ($col['titleKey'] ?? $col['data'])) }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection
