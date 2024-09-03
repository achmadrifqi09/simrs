@props([ 'drawerContent', 'title'])

<div x-data="{ show: @entangle($attributes->wire('model')) }"
     x-show="show"
     class="fixed z-50 inset-0 w-screen h-screen flex justify-end items-end flex-col"
>
    <div class="absolute left-0 right-0 top-0 bottom-0 bg-black bg-opacity-50 z-40"
         x-on:click="show = !show"
         x-show="show"
         x-transition:enter="transition ease-out duration-50"
         x-transition:enter-start="opacity-0 scale-100"
         x-transition:enter-end="opacity-1 scale-100"></div>

    <div class="z-50 min-h-1/3 fixed bottom-0 left-0 right-0"
         x-show="show"
         x-transition:enter="transition ease-out duration-100"
         x-trnasition:enter-start="-translate-y-[100vh]"
         x-trnasition:enter-end="translate-y-0"
    >
        <div class="w-full h-full bg-white px-6 p-8">
            <div class="mb-6 flex justify-between items-center max-w-screen-xl 2xl:max-w-screen-2xl mx-auto">
                <h4 class="text-xl font-medium capitalize">{{$title ?? 'Modal Title'}}</h4>
                <button x-on:click="show=!show" class="text-xl bg-gray-50 rounded-lg w-10 h-10">
                    x
                </button>
            </div>
            <div class="max-w-screen-xl 2xl:max-w-screen-2xl mx-auto">
                {{$drawerContent}}
            </div>
        </div>
    </div>
</div>
