@props(['url'])

<li class="my-2">
    <a wire:navigate href="{{$url}}"
       class="flex box-border font-medium whitespace-nowrap gap-4 text-gray-500 items-center hover:text-primary py-2 ml-4 rounded-lg">
        {{$slot}}
    </a>
</li>
