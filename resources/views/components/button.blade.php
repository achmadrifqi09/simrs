@props(['variant' => 'btn-primary'])

<button {{$attributes->merge(['class' => $variant])}} wire:loading.attr="disabled">
    {{$slot}}
</button>
