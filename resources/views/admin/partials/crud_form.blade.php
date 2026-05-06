@extends('admin.layouts.admin_layout')

@section('title', __('messages.' . $titleKey))
@section('page_title', __('messages.' . $titleKey))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route($route . '.index') }}" data-i18n="{{ $titleKey }}">{{ __('messages.' . $titleKey) }}</a></li>
    <li class="breadcrumb-item active" aria-current="page" data-i18n="{{ $mode }}">{{ __('messages.' . $mode) }}</li>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">
            <span data-i18n="{{ $mode }}">{{ __('messages.' . $mode) }}</span>
            <small class="text-muted" data-i18n="{{ $titleKey }}">{{ __('messages.' . $titleKey) }}</small>
        </h5>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($mode === 'create')
            <form method="POST" action="{{ route($route . '.store') }}" enctype="multipart/form-data" id="cruForm">
                @csrf
        @else
            <form method="POST" action="{{ route($route . '.update', $model->id) }}" enctype="multipart/form-data" id="cruForm">
                @csrf
                @method('PUT')
        @endif

            <div class="row g-3">
                @foreach($fields as $field)
                    @php
                        $name = $field['name'];
                        $i18nKey = $field['i18n'] ?? $name;
                        $label = $field['label'] ?? __('messages.' . $i18nKey);
                        $type = $field['type'] ?? 'text';
                        $value = old($name, $model->{$name} ?? ($field['default'] ?? ''));
                        $required = $field['required'] ?? false;
                        $colClass = $field['col'] ?? 'col-md-6';
                        $options = $field['options'] ?? [];
                        $placeholder = $field['placeholder'] ?? '';
                    @endphp
                    <div class="{{ $colClass }}">
                        <label class="form-label {{ $required ? 'required' : '' }}" data-i18n="{{ $field['i18n'] ?? $name }}">{{ $label }}</label>

                        @if($type === 'text' || $type === 'email' || $type === 'tel' || $type === 'number' || $type === 'password')
                            <input type="{{ $type }}" name="{{ $name }}" class="form-control" value="{{ $value }}" {{ $required ? 'required' : '' }} placeholder="{{ $placeholder }}"
                                @if(isset($field['step'])) step="{{ $field['step'] }}" @endif
                                @if(isset($field['min'])) min="{{ $field['min'] }}" @endif
                                @if(isset($field['max'])) max="{{ $field['max'] }}" @endif >
                        @elseif($type === 'textarea')
                            <textarea name="{{ $name }}" class="form-control" rows="{{ $field['rows'] ?? 3 }}" {{ $required ? 'required' : '' }} placeholder="{{ $placeholder }}">{{ $value }}</textarea>
                        @elseif($type === 'date')
                            <input type="text" name="{{ $name }}" class="form-control js-flatpickr" value="{{ $value }}" {{ $required ? 'required' : '' }} placeholder="YYYY-MM-DD">
                        @elseif($type === 'datetime')
                            <input type="text" name="{{ $name }}" class="form-control js-flatpickr-datetime" value="{{ $value }}" {{ $required ? 'required' : '' }} placeholder="YYYY-MM-DD HH:mm">
                        @elseif($type === 'time')
                            <input type="text" name="{{ $name }}" class="form-control js-flatpickr-time" value="{{ $value }}" {{ $required ? 'required' : '' }} placeholder="HH:mm">
                        @elseif($type === 'month')
                            <input type="text" name="{{ $name }}" class="form-control js-flatpickr-month" value="{{ $value }}" {{ $required ? 'required' : '' }} placeholder="YYYY-MM">
                        @elseif($type === 'select')
                            <select name="{{ $name }}{{ ($field['multiple'] ?? false) ? '[]' : '' }}" class="form-select js-tom-select" {{ ($field['multiple'] ?? false) ? 'multiple' : '' }} {{ $required ? 'required' : '' }}>
                                @if(! ($field['multiple'] ?? false))
                                    <option value=""></option>
                                @endif
                                @foreach($options as $optVal => $optLabel)
                                    @php
                                        $sel = is_array($value)
                                            ? in_array($optVal, $value)
                                            : (string) $value === (string) $optVal;
                                    @endphp
                                    <option value="{{ $optVal }}" {{ $sel ? 'selected' : '' }}>{{ $optLabel }}</option>
                                @endforeach
                            </select>
                        @elseif($type === 'checkbox')
                            <div class="form-check form-switch">
                                <input type="hidden" name="{{ $name }}" value="0">
                                <input type="checkbox" name="{{ $name }}" value="1" class="form-check-input" {{ $value ? 'checked' : '' }}>
                            </div>
                        @elseif($type === 'file')
                            <input type="file" name="{{ $name }}" class="form-control" {{ $required && $mode === 'create' ? 'required' : '' }} accept="{{ $field['accept'] ?? '' }}">
                            @if($mode === 'edit' && !empty($model->{$name}))
                                <div class="mt-2">
                                    <small class="text-muted">Current: {{ $model->{$name} }}</small>
                                </div>
                            @endif
                        @endif

                        @if(isset($field['help']))
                            <small class="form-text text-muted">{{ $field['help'] }}</small>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> <span data-i18n="save">{{ __('messages.save') }}</span>
                </button>
                <a href="{{ route($route . '.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> <span data-i18n="cancel">{{ __('messages.cancel') }}</span>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
