<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{config('app.style-default-mode')}}"
      data-theme="dracula">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name', 'Saas boilerplate') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <link rel="icon" sizes="64x64" href="{{ asset('img/icon.png') }}" type="image/x-icon">
    @vite('resources/css/app.css')
    @include('components.partials.crisp')
    @include('components.partials.gtag')
    <meta name="description" content="{{$metaDescription??''}}">
    <meta name="keywords" content="{{ $metaKeywords??''}}">
    <link rel="canonical" href="{{ $canonical}}">
</head>
<body class="font-sans antialiased">
@include('components.partials.promokit')
<div class="min-h-screen bg-base-300">
    <nav class="border-b border-base-200 bg-base-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    @include('components.partials.application-logo')
                </div>
            </div>
        </div>
    </nav>
    <header class="bg-base-100 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex flex-row justify-between">
            <h1 class="font-semibold text-xl leading-tight">{{ $title }}</h1>
            <div class="hover:cursor-pointer">
                <a href="{{ url()->previous() }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-undo-2">
                        <path d="M9 14 4 9l5-5"/>
                        <path d="M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5v0a5.5 5.5 0 0 1-5.5 5.5H11"/>
                    </svg>
                </a>
            </div>
        </div>
    </header>
    <main>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-base-100 overflow-hidden shadow-sm sm:rounded-lg ">
                    <div class="p-6 space-y-5 layout">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
