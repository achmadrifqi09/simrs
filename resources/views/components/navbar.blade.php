<nav class="sticky bg-opacity-85 backdrop-blur top-0 z-30 flex items-center justify-between h-max bg-white rounded-tl-3xl py-4 border-box px-8 w-[100vw] md:w-full border-b border-b-gray-200">
    <div class="flex gap-4 items-center">
        <button
            x-on:click="isMenuBar = !isMenuBar"
            class="bg-gray-50 aspect-square w-10 h-10 flex items-center justify-center rounded-lg text-gray-500 hover:text-primary">
            <span class="ic-list block w-6 h-6"></span>
        </button>
    </div>
    <button class="flex h-10 items-center gap-2 bg-gray-100 p-1 rounded-full">
        <div class="h-9 w-9 flex items-center justify-center bg-primary border-4 border-red-200 rounded-full">
            <span class="block text-center uppercase text-white font-lg">{{substr(auth()->user()->username, 0, 1)}}</span>
        </div>
        <p class="capitalize mr-2.5">{{auth()->user()->username}}</p>
    </button>
</nav>
