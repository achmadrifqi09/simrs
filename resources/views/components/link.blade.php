@props(['url' => '#', 'variant' => 'btn-primary'])

<a href="{{$url}}" {{$attributes->merge(['class' => $variant])}} wire:navigate>
    {{$slot}}
</a>
