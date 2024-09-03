<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? '' }} - RSU UMM</title>

    <link rel="icon" href="{{asset('images/logo-rs.webp')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{asset('css/icons.css')}}">

    @vite('resources/css/app.css')
    @livewireStyles
    @filamentStyles
</head>
<body class="antialiased overflow-x-hidden">
<div class="w-screen h-dvh overflow-hidden box-border text-gray-900">
    <main class="max-w-screen-xl 2xl:max-w-screen-2xl mx-auto pt-6 px-6 flex flex-col h-full">
        <header class="flex gap-2 md:gap-6 justify-center flex-col md:flex-row md:justify-between items-center mb-6">
            <img src="{{asset('images/logo-rs.webp')}}" alt="Logo RS UMM" class="w-28 hidden md:block">
            <div class="text-center">
                <h3 class="text-xl md:text-3xl font-bold">Rumah Sakit Umum Universitas<br>Muhammadiyah Malang</h3>
                <span class="block text-gray-600">Sistem Antrean Online</span>
                <div class="gap-2 mt-2 mx-auto text-lg w-max hidden md:flex">
                    <p id="currentDate">{{$data['currentDate']}}</p>
                    <p id="currentTime">{{$data['currentTime']}}</p>
                </div>
            </div>
            <a href="https://sim.ummhospital.com/antrian/mjkn" class="flex flex-col items-center">
                <img src="{{asset('images/logo_mjkn.png')}}" alt="Logo Mobile JKN" class="w-[88px] hidden md:block">
                <span class="block mt-2 font-semibold text-[#065273] text-center">ANTREAN MJKN</span>
            </a>
        </header>
        <div class="flex justify-between items-center md:pt-4 pb-2" id="toolbar">
        </div>
        <div class="overflow-y-auto flex-1 flex-col rounded-lg">
            {{$slot}}
        </div>

        <footer class="py-2.5 mt-1 text-center">
            <span class="text-gray-600 text-sm bg-white">Developed by IT RSU UMM 2024 | App v.1.0 </span>
        </footer>
    </main>
</div>
@livewireScripts
@vite('resources/js/app.js')
</body>
</html>
