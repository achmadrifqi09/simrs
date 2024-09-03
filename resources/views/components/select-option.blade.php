@props(['value', 'selected' => false])
<option value="{{$value}}" {{$selected ? 'selected' : ''}} {{$attributes->merge(['$selected', 'disabled'])}}>
    {{$slot}}
</option>
