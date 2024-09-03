@props(['id' ,'label' => 'label', 'disabled' => false])
<div class="w-full">
    <label for="{{$id}}" class="block mb-1 text-gray-500">{{$label}}</label>
    <select id="{{$id}}"
        {{
            $disabled ? '$disabled' : ''
        }}
        {{
            $attributes->merge(['class' => 'border px-3 py-2 border-gray-300 w-full focus:outline-none focus:ring-primary focus:border-primary rounded-lg'])
        }}
    >
        {{$slot}}
    </select>
</div>
