@props(['icon', 'value', 'description'])

<div class="p-2.5 bg-white w-full sm:w-auto rounded-xl flex gap-4 items-center border border-gray-200 relative overflow-hidden">
    <div class="p-2 rounded-full bg-red-200 relative z-10 shadow-md">
        <div class="aspect-square w-16 h-16 bg-primary text-white flex justify-center items-center rounded-full">
            {{$icon}}
        </div>
    </div>
    <div class="relative z-10">
        <h3 class="text-3xl font-bold mb-1">{{$value}}</h3>
        <p class="text-gray-500 text-sm max-w-[16ch]">{{$description}}</p>
    </div>
</div>
