@extends('admin.layouts.admin_layout')

@section('title', __('messages.' . $titleKey))
@section('page_title', __('messages.' . $titleKey))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route($route . '.index') }}" data-i18n="{{ $titleKey }}">{{ __('messages.' . $titleKey) }}</a></li>
    <li class="breadcrumb-item active" aria-current="page" data-i18n="show">{{ __('messages.show') }}</li>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <span data-i18n="show">{{ __('messages.show') }}</span>
            <small class="text-muted" data-i18n="{{ $titleKey }}">{{ __('messages.' . $titleKey) }}</small>
        </h5>
        <div class="d-flex gap-2">
            <a href="{{ route($route . '.edit', $model->id) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-pencil"></i> <span data-i18n="edit">{{ __('messages.edit') }}</span>
            </a>
            <a href="{{ route($route . '.index') }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left"></i> <span data-i18n="back">{{ __('messages.back') }}</span>
            </a>
        </div>
    </div>
    <div class="card-body">
        <dl class="row mb-0">
            @foreach($fields as $field)
                @php
                    $name = $field['name'];
                    $label = $field['label'] ?? __('messages.' . $name);
                    $type = $field['type'] ?? 'text';
                    $value = $model->{$name} ?? null;

                    if ($type === 'select' && !empty($field['options'][$value])) {
                        $value = $field['options'][$value];
                    } elseif ($type === 'checkbox') {
                        $value = $value ? __('messages.yes') : __('messages.no');
                    } elseif ($type === 'date' && $value) {
                        $value = \Illuminate\Support\Carbon::parse($value)->format('Y-m-d');
                    } elseif ($type === 'datetime' && $value) {
                        $value = \Illuminate\Support\Carbon::parse($value)->format('Y-m-d H:i');
                    }
                @endphp
                <dt class="col-sm-3 text-muted" data-i18n="{{ $field['i18n'] ?? $name }}">{{ $label }}</dt>
                <dd class="col-sm-9">{{ $value !== null && $value !== '' ? $value : '—' }}</dd>
            @endforeach

            <dt class="col-sm-3 text-muted" data-i18n="created_at">{{ __('messages.created_at') }}</dt>
            <dd class="col-sm-9">{{ $model->created_at?->format('Y-m-d H:i') }}</dd>
            <dt class="col-sm-3 text-muted" data-i18n="updated_at">{{ __('messages.updated_at') }}</dt>
            <dd class="col-sm-9">{{ $model->updated_at?->format('Y-m-d H:i') }}</dd>
        </dl>
    </div>
</div>
@endsection
