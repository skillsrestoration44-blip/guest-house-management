<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-locale="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.sign_in') }} | {{ __('messages.app_name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <style>
        body { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1rem; }
        .login-card { max-width: 460px; width: 100%; background: #fff; border-radius: 1rem; padding: 2rem; box-shadow: 0 20px 50px rgba(0,0,0,.2); }
    </style>
</head>
<body>
<div class="login-card">
    <div class="text-center mb-4">
        <i class="bi bi-house-fill text-primary fs-1"></i>
        <h3 class="mt-2">{{ __('messages.app_name') }}</h3>
        <p class="text-muted">{{ __('messages.sign_in') }}</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login.attempt') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label required">{{ __('messages.username') }} / {{ __('messages.email') }}</label>
            <input type="text" name="login" class="form-control" value="{{ old('login') }}" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label required">{{ __('messages.password') }}</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" name="remember" id="remember" class="form-check-input" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember" class="form-check-label">{{ __('messages.remember_me') }}</label>
        </div>
        <button type="submit" class="btn btn-primary w-100">{{ __('messages.sign_in') }}</button>
    </form>

    <div class="text-center mt-3">
        <a href="{{ url('/locale/switch') }}?locale=en" class="text-decoration-none">EN</a> |
        <a href="{{ url('/locale/switch') }}?locale=km" class="text-decoration-none">KM</a>
    </div>
</div>
</body>
</html>
