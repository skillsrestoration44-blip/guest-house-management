@extends('admin.layouts.admin_layout')

@section('title', __('messages.roles'))
@section('page_title', __('messages.roles'))
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route($route . '.index') }}" data-i18n="roles">{{ __('messages.roles') }}</a></li>
  <li class="breadcrumb-item active" aria-current="page" data-i18n="edit">{{ __('messages.edit') }}</li>
@endsection

@section('content')
  @include('admin.roles._form')
@endsection
