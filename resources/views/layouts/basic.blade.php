<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? '' }} | RSU UMM</title>
    <link rel="stylesheet" href="{{asset('css/icons.css')}}">

    @filamentStyles
    @livewireStyles

    @vite('resources/css/app.css')

    <link rel="icon" href="{{asset('images/logo-rs.webp')}}" type="image/x-icon">
</head>
<body class="antialiased">
    <main class="text-gray-950">
        {{$slot}}
    </main>
    @vite('resources/js/app.js')
    @filamentScripts
    @livewireScripts
</body>
</html>
