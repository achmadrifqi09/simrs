@props([ 'modalContent', 'title', 'modalAction'])

<div
    x-cloak
    x-data="{ show: @entangle($attributes->wire('model')) }"
     x-show="show ?? false"
     class="fixed z-50 inset-0 w-screen h-screen flex justify-center items-center flex-col"
>
    <div class="absolute left-0 right-0 top-0 bottom-0 bg-black bg-opacity-60 z-40"
         x-on:click="show = !show"
         x-show="show"
         x-transition:enter="transition ease-out duration-50"
         x-transition:enter-start="opacity-0 scale-100"></div>

    <div class="relative p-4 z-50 w-full mx-6 md:3/5 lg:w-2/4 h-auto"
         x-show="show"
         x-trap.inert.noscroll="show"
         x-transition:enter="ease-out duration-100"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
    >
        <div class="w-full bg-white p-6 rounded-xl shadow-lg">
            <div class="mb-6 flex justify-between items-center">
                <h4 class="text-lg capitalize">{{$title ?? 'Modal Title'}}</h4>
                <button x-on:click="show=!show" class="text-lg bg-gray-50 rounded-lg w-8 h-8">
                    x
                </button>
            </div>
            <div>
                {{$modalContent}}
            </div>
            <div class="flex justify-end items-center mt-4 gap-2">
                @if(isset($modalAction))
                    {{$modalAction}}
                @endif
            </div>
        </div>
    </div>
</div>
