@props(['title', 'content'])
<div class="rounded-xl border border-gray-100 h-max p-4 bg-white bg-opacity-60 shadow-sm backdrop-blur-sm relative overflow-hidden">
    <div class="before:content-[''] before:w-8/12 before:h-full before:bg-circles before:bg-cover before:-z-[1]
    before:absolute before:bg-no-repeat before:bg-top before:-bottom-32 before:-right-64 before:opacity-[2%]"></div>
    <div class="rounded-xl">
        <h3 class="text-xl w-max flex gap-2 items-center font-semibold capitalize">
            {{$title}}
        </h3>
    </div>
    <div class="mt-4 rounded-lg">
        {{$content}}
    </div>
</div>
