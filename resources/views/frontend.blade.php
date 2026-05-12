<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="locale" content="{{ app()->getLocale() }}">
    <title>{{ config('app.name', 'Guest House') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <link rel="preload" href="{{ asset('assets/fonts/NotoSansKhmer.ttf') }}" as="font" type="font/ttf" crossorigin>

    @vite(['resources/frontend/main.js'])
</head>
<body>
    <div id="frontend-app"></div>
</body>
</html>
