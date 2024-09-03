@props(['url' => '#', 'active' => false])

<li>
    <a wire:navigate
       href="{{$url}}"
       class="{{$active ? 'menu-active' : 'menu'}}"
    >
        {{ $slot }}
    </a>
</li>
