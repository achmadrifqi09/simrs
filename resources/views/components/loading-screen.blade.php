<div
    {{$attributes}}
     class="fixed inset-0 z-[999999]">
    <div class="absolute inset-0 bg-black opacity-40 z-40"></div>
    <div class="flex flex-col justify-center items-center w-full h-full relative z-50">
        <div class="flex flex-col items-center">
            <lottie-player
                autoplay
                loop
                mode="normal"
                src="{{asset('json/heart-rate.json')}}"
                class="w-[280px]"
            >
            </lottie-player>
            <p class="text-white text-lg">Sedang memuat, mohon tunggu ...</p>
        </div>
    </div>
</div>
