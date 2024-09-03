@props(['menuDropdownItems',  'active', 'id'])

<li x-data="{showDropdownItems : false}">
    <button x-on:click="selectedItem = selectedItem === '{{$id}}' ? null : '{{$id}}';"
            class="{{$active ? 'menu-active' : 'menu'}}">
            <span class="flex gap-4 items-center font-medium">
                {{ $slot }}
            </span>
        <span class="ic-chevron-down" x-show="selectedItem !== '{{$id}}'"></span>
        <span class="ic-chevron-up" x-show="selectedItem === '{{$id}}'"></span>
    </button>
    <ul class="ml-9" x-show="selectedItem === '{{$id}}'">
        {{$menuDropdownItems}}
    </ul>
</li>
