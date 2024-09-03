@props(['slug' => null])

<div class="flex gap-1.5 items-center mb-2">
    @foreach(explode('/', request()->path()) as $key => $path)
        @if(!is_numeric($path))
            <span class="text-sm text-gray-500 capitalize">{{$path}}</span>
        @endif
        @if(count(explode('/', request()->path())) -1 !== $key && !is_numeric($path))
            <span class="ic-chevron-right text-gray-500 w-5 h-5"></span>
        @endif

    @endforeach
    @if($slug)
        <span class="text-sm text-gray-500 capitalize">{{$slug}}</span>
    @endif
</div>
