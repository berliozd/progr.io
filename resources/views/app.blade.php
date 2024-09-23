<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{config('app.style-default-mode')}}"
      data-theme="dracula">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title inertia>{{ config('app.name', 'Saas boilerplate') }}</title>
    <link rel="icon" sizes="64x64" href="{{ asset('img/icon.png') }}" type="image/x-icon">
    @include('components.partials.gtag')
    @include('components.partials.ad-site')
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<body class="font-sans antialiased">
@include('components.partials.promokit')
@inertia
</body>
</html>
