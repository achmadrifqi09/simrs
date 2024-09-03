<div class="bg-white pt-4">
    <div
        x-data="{ activeItem: @entangle($attributes->wire('model')) }"
        x-init="activeItem = '{{$items[0]['key']}}'"
        {{$attributes->merge(['class' => 'flex justify-between items-center bg-gray-100 rounded-lg sticky top-[106px] p-1'])}}>
        @foreach($items as $item)
            <button
                x-on:click="activeItem = '{{$item['key']}}'"
                :class="activeItem === '{{ $item['key'] }}' ? 'w-full text-center px-4 py-2 bg-secondary rounded-lg text-white shadow' : 'w-full rounded-lg text-center px-4 py-2 text-gray-500 bg-gray-100'">
                {{$item['label']}}
            </button>
        @endforeach
    </div>
</div>
