<div class="min-h-72 w-auto aspect-[6/3.5] bg-gray-50 rounded-2xl text-[18px] relative shadow">
    <div class="w-full h-1/3 absolute bg-gradient-to-r from-[#006F3D] via-[#1DAA7C] to-[#007041] rounded-t-2xl flex justify-center px-4 items-center">
        <img src="{{asset('images/garuda.png')}}" alt="Logo Garuda" class="w-[56px] absolute left-4">
        <h4 class="font-medium text-white text-2xl">Kartu Indonesia Sehat</h4>
        <img src="{{asset('images/right-logo.png')}}" alt="Logo" class="w-[64px] absolute right-4">
    </div>
    <div class="w-full h-full flex justify-center items-end rounded-2xl relative">
        <img src="{{asset('images/indonesia-map.webp')}}" alt="Indonesia Map" class=" opacity-[8%] z-0">
        <div class="absolute left-0 right-0 bottom-0 top-1/3 p-4">
            <div class="flex gap-4">
                {{$slot}}
            </div>
        </div>
    </div>
</div>
