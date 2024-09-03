<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? '' }} - RSU UMM</title>

    <link rel="icon" href="{{ asset('images/logo-rs.webp') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{asset('css/icons.css')}}">

    @filamentStyles
    @livewireStyles


    @vite('resources/css/app.css')
</head>

<body class="antialiased overflow-x-hidden">
    <div
        class="w-full h-dvh flex bg-gray-50 text-gray-900 leading-relaxed"
        x-data="{isMenuBar : true}"
        x-init="isMenuBar = window.innerWidth < 768 ? false : true"
        x-on:resize.window="isMenuBar = window.innerWidth > 768;"
    >
        <x-offline-alert/>
        <x-menu-bar/>
        <div class="relative overflow-x-hidden bg-white flex flex-col overflow-y-auto bg-light w-[100vw] md:w-full mx-auto direction-ltr border-l border-l-gray-200">
            <x-navbar/>
            <main class="w-[100vw] md:w-full px-6 py-6 flex-1">
                {{$slot}}
            </main>
            <footer class="flex py-3 justify-center items-center text-sm text-gray-500 w-[100vw] md:w-full bg-gray-50">
                <span class="text-gray-600 text-sm">Developed by IT RSU UMM 2024 | App v.1.0 </span>
            </footer>
        </div>
    </div>
    <x-toast/>
    @vite('resources/js/app.js')
    @filamentScripts
    @livewireScripts
</body>

</html>
