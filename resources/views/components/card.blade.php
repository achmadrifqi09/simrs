<div class="p-4 border border-gray-200 rounded-lg flex flex-col items-start justify-between">
    <h4 class="text-xl font-medium">{{$title}}</h4>
    <div class="w-full space-y-3">
        {{$body}}
    </div>
    @if(isset($footer))
        <div class="w-full">
            {{$footer}}
        </div>
    @endif
</div>
